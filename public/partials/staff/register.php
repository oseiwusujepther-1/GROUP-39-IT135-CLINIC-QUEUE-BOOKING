<?php
require __DIR__ . '/../src/bootstrap.php';
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $name  = trim($_POST['full_name'] ?? '');
    $phone = preg_replace('/\s+/', '', $_POST['phone'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $ghanaCardNumber = strtoupper(preg_replace('/\s+/', '', $_POST['ghana_card_number'] ?? ''));
    $pass  = $_POST['password'] ?? '';

    if ($name === '')                         $errors[] = 'Full name is required.';
    if (!preg_match('/^0\d{9}$/', $phone))    $errors[] = 'Enter a 10-digit phone number starting with 0.';
    if (strlen($location) > 150)             $errors[] = 'Location must be 150 characters or fewer.';
    if ($ghanaCardNumber !== '' && !preg_match('/^GHA-\d{9}-\d$/', $ghanaCardNumber)) $errors[] = 'Enter a valid Ghana Card number (for example, GHA-123456789-0).';
    if (strlen($pass) < 6)                    $errors[] = 'Password must be at least 6 characters.';

    if (!$errors) {
        try {
            $st = $db->prepare("INSERT INTO patients (full_name, phone, location, ghana_card_number, password_hash) VALUES (?,?,?,?,?)");
            $st->execute([$name, $phone, $location ?: null, $ghanaCardNumber ?: null, password_hash($pass, PASSWORD_DEFAULT)]);
            session_regenerate_id(true);
            $_SESSION['patient_id'] = (int)$db->lastInsertId();
            $_SESSION['patient_name'] = $name;
            flash('Welcome! Your account is ready.', 'success');
            redirect('/dashboard.php');
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') $errors[] = 'That phone number is already registered.';
            else throw $e;
        }
    }
}
$title = 'Register';
require __DIR__ . '/partials/header.php';
?>
<div class="row justify-content-center"><div class="col-md-5">
  <h3>Create account</h3>
  <?php foreach ($errors as $err): ?><div class="alert alert-danger"><?= e($err) ?></div><?php endforeach; ?>
  <form method="post" class="card card-body">
    <?= csrf_field() ?>
    <label class="form-label">Full name</label>
    <input class="form-control mb-3" name="full_name" value="<?= e($_POST['full_name'] ?? '') ?>" required>
    <label class="form-label">Phone (for SMS)</label>
    <input class="form-control mb-3" name="phone" placeholder="0244123456" value="<?= e($_POST['phone'] ?? '') ?>" required>
    <label class="form-label">Location (optional)</label>
    <input class="form-control mb-3" name="location" maxlength="150" value="<?= e($_POST['location'] ?? '') ?>">
    <label class="form-label">Ghana Card Number (optional)</label>
    <input class="form-control mb-3" name="ghana_card_number" maxlength="15" placeholder="GHA-123456789-0" value="<?= e($_POST['ghana_card_number'] ?? '') ?>">
    <label class="form-label">Password</label>
    <input class="form-control mb-3" type="password" name="password" required>
    <button class="btn btn-success">Register</button>
  </form>
</div></div>
<?php require __DIR__ . '/partials/footer.php'; ?>
