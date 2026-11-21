<?php
declare(strict_types=1);

function e(?string $s): string { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

function csrf_token(): string {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}
function csrf_field(): string { return '<input type="hidden" name="csrf" value="' . csrf_token() . '">'; }
function csrf_check(): void {
    if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
        http_response_code(419);
        exit('Invalid form token. Go back and try again.');
    }
}

function redirect(string $path): never { header('Location: ' . BASE_URL . $path); exit; }

function flash(?string $msg = null, string $type = 'info'): ?array {
    if ($msg !== null) { $_SESSION['flash'] = ['msg' => $msg, 'type' => $type]; return null; }
    $f = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $f;
}

function require_patient(): void { if (empty($_SESSION['patient_id'])) redirect('/login.php'); }
function require_staff(): void   { if (empty($_SESSION['staff_id']))   redirect('/staff/login.php'); }

function require_staff_role(PDO $db, array $roles): array
{
    require_staff();
    $st = $db->prepare('SELECT role, is_active FROM staff WHERE staff_id=?');
    $st->execute([(int)$_SESSION['staff_id']]);
    $staff = $st->fetch();

    if (!$staff || !(int)$staff['is_active']) {
        unset($_SESSION['staff_id'], $_SESSION['staff_name']);
        redirect('/staff/login.php');
    }
    if (!in_array($staff['role'], $roles, true)) {
        http_response_code(403);
        exit('You do not have permission to access this page.');
    }

    return $staff;
}