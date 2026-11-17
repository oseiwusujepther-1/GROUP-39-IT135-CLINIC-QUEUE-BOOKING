
<?php
require __DIR__ . '/../src/bootstrap.php';
$q = $queue->todayQueue();
$title = 'Live queue';
$refresh = 10;
require __DIR__ . '/partials/header.php';
?>
<h3 class="mb-3">Live queue <small class="text-muted fs-6">updates every 10 seconds</small></h3>
<div class="row g-3">
  <div class="col-md-5">
    <div class="card text-center border-success"><div class="card-body">
      <div class="text-muted">Now serving</div>
      <?php if ($q['called']): foreach ($q['called'] as $c): ?>
        <div class="display-3 text-success">#<?= (int)$c['queue_number'] ?></div>
      <?php endforeach; else: ?>
        <div class="display-6 text-muted">-</div>
      <?php endif; ?>
    </div></div>
  </div>
  <div class="col-md-7">
    <div class="card"><div class="card-header">Waiting (<?= count($q['waiting']) ?>)</div>
      <ul class="list-group list-group-flush">
        <?php foreach ($q['waiting'] as $w): ?>
          <li class="list-group-item d-flex justify-content-between">
            <span>#<?= (int)$w['queue_number'] ?> - <?= e($w['service_name']) ?></span>
            <?php if ($w['is_emergency']): ?><span class="badge text-bg-danger">Priority</span><?php endif; ?>
          </li>
        <?php endforeach; ?>
        <?php if (!$q['waiting']): ?><li class="list-group-item text-muted">Nobody waiting.</li><?php endif; ?>
      </ul>
    </div>
  </div>
</div>
<?php require __DIR__ . '/partials/footer.php'; ?>
