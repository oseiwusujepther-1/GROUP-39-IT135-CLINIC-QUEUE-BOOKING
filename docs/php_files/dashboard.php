<?php
require __DIR__ . '/../src/bootstrap.php';
require_patient();
$pid = (int)$_SESSION['patient_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $apptId = (int)($_POST['appointment_id'] ?? 0);
    try {
        if (($_POST['action'] ?? '') === 'checkin') {
            $queue->checkIn($apptId, $pid);
            flash('You are checked in. See your position below.', 'success');
        } elseif (($_POST['action'] ?? '') === 'cancel') {
            $db->prepare("UPDATE appointments SET status='cancelled' WHERE appointment_id=? AND patient_id=? AND status='booked'")
               ->execute([$apptId, $pid]);
            flash('Appointment cancelled.', 'warning');
        }
    } catch (RuntimeException $e) {
        flash($e->getMessage(), 'danger');
    }
    redirect('/dashboard.php');
}

$st = $db->prepare(
    "SELECT a.*, s.service_name, q.queue_id, q.queue_number, q.status AS queue_status
       FROM appointments a
       JOIN services s ON s.service_id = a.service_id
       LEFT JOIN queue_entries q ON q.appointment_id = a.appointment_id
      WHERE a.patient_id=?
      ORDER BY a.appointment_date DESC, a.slot_time DESC LIMIT 20"
);
$st->execute([$pid]);
$rows = $st->fetchAll();
$photoSt = $db->prepare("SELECT passport_photo FROM patients WHERE patient_id=?");
$photoSt->execute([$pid]);
$passportPhoto = $photoSt->fetchColumn();

$title = 'My appointments';
$refresh = 15;
require __DIR__ . '/partials/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3>Hello, <?= e($_SESSION['patient_name'] ?? '') ?></h3>
  <div class="d-flex gap-2">
    <a class="btn btn-outline-success" href="<?= BASE_URL ?>/profile.php">My profile</a>
    <a class="btn btn-success" href="<?= BASE_URL ?>/book.php">Book appointment</a>
  </div>
</div>
<section class="patient-photo-panel d-flex align-items-center gap-3 mb-4">
  <?php if ($passportPhoto): ?>
    <img class="patient-photo" src="<?= BASE_URL ?>/patient-photo.php" alt="Your passport photo">
  <?php else: ?>
    <div class="patient-photo-placeholder" aria-hidden="true">Photo</div>
  <?php endif; ?>
  <div>
    <h5 class="mb-1">Passport photo</h5>
    <p class="text-muted mb-2">Add or update the photo on your patient profile.</p>
    <a class="btn btn-outline-success btn-sm" href="<?= BASE_URL ?>/passport-photo.php">
      <?= $passportPhoto ? 'Change photo' : 'Upload passport photo' ?>
    </a>
  </div>
</section>
<div class="table-responsive"><table class="table bg-white align-middle">
  <thead><tr><th>Date</th><th>Time</th><th>Service</th><th>Status</th><th>Queue</th><th></th></tr></thead>
  <tbody>
  <?php foreach ($rows as $r): ?>
    <tr>
      <td><?= e($r['appointment_date']) ?></td>
      <td><?= e(substr($r['slot_time'], 0, 5)) ?></td>
      <td><?= e($r['service_name']) ?></td>
      <td><span class="badge text-bg-secondary"><?= e($r['status']) ?></span></td>
      <td>
        <?php if ($r['queue_id'] && $r['queue_status'] === 'waiting'):
            [$ahead, $wait] = $queue->positionOf((int)$r['queue_id']); ?>
          No. <strong><?= (int)$r['queue_number'] ?></strong><br>
          <small><?= $ahead ?> ahead, about <?= $wait ?> min</small>
        <?php elseif ($r['queue_id']): ?>
          No. <?= (int)$r['queue_number'] ?> (<?= e($r['queue_status']) ?>)
        <?php else: ?>-<?php endif; ?>
      </td>
      <td>
        <?php if ($r['status'] === 'booked'): ?>
          <form method="post" class="d-inline"><?= csrf_field() ?>
            <input type="hidden" name="appointment_id" value="<?= (int)$r['appointment_id'] ?>">
            <a class="btn btn-sm btn-outline-success" href="<?= BASE_URL ?>/edit-appointment.php?id=<?= (int)$r['appointment_id'] ?>">Edit</a>
            <?php if ($r['appointment_date'] === date('Y-m-d')): ?>
              <button name="action" value="checkin" class="btn btn-sm btn-success">Check in</button>
            <?php endif; ?>
            <button name="action" value="cancel" class="btn btn-sm btn-outline-danger"
                    onclick="return confirm('Cancel this appointment?')">Cancel</button>
          </form>
        <?php endif; ?>
      </td>
    </tr>
  <?php endforeach; ?>
  <?php if (!$rows): ?><tr><td colspan="6" class="text-center text-muted">No appointments yet.</td></tr><?php endif; ?>
  </tbody>
</table></div>
<?php require __DIR__ . '/partials/footer.php'; ?>
