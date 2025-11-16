<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
  <div class="row">
    <div class="col-12 page-section">

      <!-- Quick actions / tiles -->
      <div class="row g-3">
        <div class="col-md-4">
          <div class="card card-soft h-100">
            <div class="card-body">
              <h5 class="card-title mb-2"><i class="fas fa-calendar-plus me-2"></i>Add Event</h5>
              <p class="text-muted mb-3">Create a new pageant event and set rounds, judges, and contestants.</p>
              <a href="<?= base_url('events/create') ?>" class="btn btn-primary btn-sm">Create Event</a>
              <a href="#" class="btn btn-outline-secondary btn-sm ms-2">Import</a>
            </div>
          </div>
        </div>

        <?php if (!empty($events)): ?>
          <?php foreach ($events as $ev): ?>
          <div class="col-md-4">
            <div class="card h-100 <?= !empty($ev['is_active']) ? 'border-primary' : 'border-secondary' ?>">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                  <h5 class="card-title mb-2 mb-0"><i class="fas fa-crown me-2"></i><?= esc($ev['name']) ?></h5>
                  <?php if (!empty($ev['is_active'])): ?>
                    <span class="badge bg-success">Active</span>
                  <?php endif; ?>
                </div>
                <p class="text-muted mb-3">
                  <?php if (!empty($ev['is_active'])): ?>
                    View dashboard for this event.
                  <?php else: ?>
                    Activate this event to open its dashboard.
                  <?php endif; ?>
                </p>
                <div class="d-flex gap-2">
                  <?php if (!empty($ev['is_active'])): ?>
                    <a class="btn btn-outline-primary btn-sm" href="<?= base_url('dashboard') ?>">Open Dashboard</a>
                  <?php else: ?>
                    <button class="btn btn-outline-secondary btn-sm" disabled>Open Dashboard</button>
                  <?php endif; ?>
                  <form method="post" action="<?= site_url('events/activate/' . (int)$ev['id']) ?>">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-sm btn-secondary" <?= !empty($ev['is_active']) ? 'disabled' : '' ?>>Set Active</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        <?php endif; ?>

<?= $this->endSection() ?>
