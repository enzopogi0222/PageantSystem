<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<div class="container py-3">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
      <?php endif; ?>
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Create Event</h5>
          <a href="<?= base_url('admin') ?>" class="btn btn-sm btn-outline-secondary">Back</a>
        </div>
        <div class="card-body">
          <form method="post" action="<?= site_url('events') ?>">
            <?= csrf_field() ?>
            <div class="mb-3">
              <label for="name" class="form-label">Event Name</label>
              <input type="text" class="form-control" id="name" name="name" value="<?= esc(old('name')) ?>" required />
            </div>
            <div class="mb-3">
              <label for="description" class="form-label">Description</label>
              <textarea class="form-control" id="description" name="description" rows="3"><?= esc(old('description')) ?></textarea>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="event_date" class="form-label">Event Date</label>
                <input type="date" class="form-control" id="event_date" name="event_date" value="<?= esc(old('event_date')) ?>" />
              </div>
              <div class="col-md-6 mb-3">
                <label for="location" class="form-label">Location</label>
                <input type="text" class="form-control" id="location" name="location" value="<?= esc(old('location')) ?>" />
              </div>
            </div>
            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary">Save Event</button>
              <a href="<?= base_url('admin') ?>" class="btn btn-outline-secondary">Cancel</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
