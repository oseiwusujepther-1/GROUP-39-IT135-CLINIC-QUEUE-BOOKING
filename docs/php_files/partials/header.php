<?php /* expects $title; optional $refresh (seconds) */ $f = flash(); ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php if (!empty($refresh)): ?><meta http-equiv="refresh" content="<?= (int)$refresh ?>"><?php endif; ?>
  <title><?= e($title ?? 'Clinic Queue') ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?= BASE_URL ?>/styles.css" rel="stylesheet">
</head>
<body class="clinic-app">
<nav class="navbar navbar-expand navbar-dark clinic-navbar mb-4">
  <div class="container">
    <a class="navbar-brand" href="<?= BASE_URL ?>/">Clinic Queue</a>
    <div class="navbar-nav ms-auto">
      <a class="nav-link" href="<?= BASE_URL ?>/queue.php">Live queue</a>
      <?php if (!empty($_SESSION['patient_id'])): ?>
        <a class="nav-link" href="<?= BASE_URL ?>/dashboard.php">My appointments</a>
        <a class="nav-link" href="<?= BASE_URL ?>/profile.php">My profile</a>
        <a class="nav-link" href="<?= BASE_URL ?>/logout.php">Logout</a>
      <?php elseif (!empty($_SESSION['staff_id'])): ?>
        <a class="nav-link" href="<?= BASE_URL ?>/staff/dashboard.php">Staff dashboard</a>
        <?php if (($_SESSION['staff_role'] ?? '') === 'admin'): ?>
          <a class="nav-link" href="<?= BASE_URL ?>/staff/manage.php">Manage records</a>
        <?php endif; ?>
        <a class="nav-link" href="<?= BASE_URL ?>/logout.php">Logout</a>
      <?php else: ?>
        <a class="nav-link" href="<?= BASE_URL ?>/login.php">Patient login</a>
        <a class="nav-link" href="<?= BASE_URL ?>/staff/login.php">Staff</a>
      <?php endif; ?>
    </div>
  </div>
</nav>
<div class="container">
<?php if ($f): ?><div class="alert alert-<?= e($f['type']) ?>"><?= e($f['msg']) ?></div><?php endif; ?>
