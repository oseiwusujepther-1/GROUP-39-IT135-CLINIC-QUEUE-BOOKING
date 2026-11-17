<?php
require __DIR__ . '/../src/bootstrap.php';
require_patient();

$patientId = (int)$_SESSION['patient_id'];
$getPatient = $db->prepare('SELECT full_name, phone, email, location, ghana_card_number, password_hash FROM patients WHERE patient_id=? AND is_active=1');
$getPatient->execute([$patientId]);
$patient = $getPatient->fetch();
if (!$patient) {
    unset($_SESSION['patient_id'], $_SESSION['patient_name']);
    redirect('/login.php');
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    if (($_POST['action'] ?? '') === 'deactivate') {
        if (!password_verify($_POST['current_password'] ?? '', $patient['password_hash'])) {
            $errors[] = 'Enter your current password to deactivate your account.';
        } else {
            $db->prepare('UPDATE patients SET is_active=0 WHERE patient_id=?')->execute([$patientId]);
            unset($_SESSION['patient_id'], $_SESSION['patient_name']);
            session_regenerate_id(true);
            flash('Your account has been deactivated. Appointment history has been kept.', 'success');
            redirect('/login.php');
        }
    } elseif (($_POST['action'] ?? '') === 'save') {
        $name = trim($_POST['full_name'] ?? '');
        $phone = preg_replace('/\s+/', '', $_POST['phone'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $location = trim($_POST['location'] ?? '');
        $ghanaCardNumber = strtoupper(preg_replace('/\s+/', '', $_POST['ghana_card_number'] ?? ''));
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        if ($name === '' || strlen($name) > 100) $errors[] = 'Enter your name (up to 100 characters).';
        if (!preg_match('/^0\d{9}$/', $phone)) $errors[] = 'Enter a 10-digit phone number starting with 0.';
        if ($email !== '' && (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 120)) $errors[] = 'Enter a valid email address.';
        if (strlen($location) > 150) $errors[] = 'Location must be 150 characters or fewer.';
        if ($ghanaCardNumber !== '' && !preg_match('/^GHA-\d{9}-\d$/', $ghanaCardNumber)) $errors[] = 'Enter a valid Ghana Card number (for example, GHA-123456789-0).';
        if ($newPassword !== '' && strlen($newPassword) < 6) $errors[] = 'New password must be at least 6 characters.';
        if ($newPassword !== '' && !password_verify($currentPassword, $patient['password_hash'])) $errors[] = 'Enter your current password to change it.';
        if (!$errors) {
            $sql = 'UPDATE patients SET full_name=?, phone=?, email=?, location=?, ghana_card_number=?' . ($newPassword !== '' ? ', password_hash=?' : '') . ' WHERE patient_id=?';
            $values = [$name, $phone, $email ?: null, $location ?: null, $ghanaCardNumber ?: null];
            if ($newPassword !== '') $values[] = password_hash($newPassword, PASSWORD_DEFAULT);
            $values[] = $patientId;
            try {
                $db->prepare($sql)->execute($values);
                $_SESSION['patient_name'] = $name;
                if ($newPassword !== '') session_regenerate_id(true);
                flash('Your profile has been updated.', 'success');
                redirect('/profile.php');
            } catch (PDOException $e) {
                if ($e->getCode() === '23000') $errors[] = 'That phone number is already registered.';
                else throw $e;
            }
        }
        $patient['full_name'] = $name;
        $patient['phone'] = $phone;
        $patient['email'] = $email;
        $patient['location'] = $location;
        $patient['ghana_card_number'] = $ghanaCardNumber;
    }
}

$title = 'My profile';
require __DIR__ . '/partials/header.php';
?>
<div class="row justify-content-center"><div class="col-md-6">
  <h3>My profile</h3>
  <?php foreach ($errors as $error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endforeach; ?>
  <form method="post" class="card card-body mb-4">
    <?= csrf_field() ?>
    <input type="hidden" name="action" value="save">
    <label class="form-label">Full name</label><input class="form-control mb-3" name="full_name" maxlength="100" value="<?= e($patient['full_name']) ?>" required>
    <label class="form-label">Phone</label><input class="form-control mb-3" name="phone" value="<?= e($patient['phone']) ?>" required>
    <label class="form-label">Email (optional)</label><input class="form-control mb-3" type="email" name="email" maxlength="120" value="<?= e($patient['email'] ?? '') ?>">
    <label class="form-label">Location (optional)</label><input class="form-control mb-3" name="location" maxlength="150" value="<?= e($patient['location'] ?? '') ?>">
    <label class="form-label">Ghana Card Number (optional)</label><input class="form-control mb-3" name="ghana_card_number" maxlength="15" placeholder="GHA-123456789-0" value="<?= e($patient['ghana_card_number'] ?? '') ?>">
    <label class="form-label">Current password (required only to change password)</label><input class="form-control mb-3" type="password" name="current_password">
    <label class="form-label">New password (optional)</label><input class="form-control mb-3" type="password" name="new_password" minlength="6">
    <button class="btn btn-success">Save profile</button>
  </form>
  <form method="post" class="card card-body border-danger" onsubmit="return confirm('Deactivate your account? Your appointment history will be kept, but you will no longer be able to log in.')">
    <?= csrf_field() ?>
    <input type="hidden" name="action" value="deactivate">
    <label class="form-label">Current password to deactivate account</label>
    <input class="form-control mb-3" type="password" name="current_password" required>
    <button class="btn btn-outline-danger">Deactivate my account</button>
  </form>
  <a class="btn btn-link mt-2" href="<?= BASE_URL ?>/dashboard.php">Back to appointments</a>
</div></div>
<?php require __DIR__ . '/partials/footer.php'; ?>

