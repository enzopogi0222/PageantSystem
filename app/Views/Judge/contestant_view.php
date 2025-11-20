<?= $this->extend('layouts/template') ?>

<?= $this->section('content') ?>
<div class="container py-3">
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= esc($error) ?></div>
    <?php endif; ?>
    <?php if (!empty($message)): ?>
        <div class="alert alert-success"><?= esc($message) ?></div>
    <?php endif; ?>

    <a href="<?= esc(base_url('judge')) ?>" class="btn btn-sm btn-outline-secondary mb-3"><i class="fa fa-arrow-left"></i> Back to dashboard</a>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row g-3 align-items-start">
                <div class="col-12 col-md-auto">
                    <?php $photo = $contestant['photo_url'] ?? null; ?>
                    <?php if (!empty($photo)): ?>
                        <img src="<?= esc($photo) ?>" alt="Photo" class="rounded border" style="width:220px; height:220px; object-fit:cover;"
                             onerror="this.style.display='none'; const p=this.nextElementSibling; if(p) p.style.display='flex';">
                        <div class="bg-light border rounded align-items-center justify-content-center" style="width:220px; height:220px; display:none;">No Photo</div>
                    <?php else: ?>
                        <div class="bg-light border rounded d-flex align-items-center justify-content-center" style="width:220px; height:220px;">No Photo</div>
                    <?php endif; ?>
                </div>
                <div class="col">
                    <h3 class="mb-1">
                        <?= esc(($contestant['first_name'] ?? '').' '.($contestant['last_name'] ?? '')) ?>
                        <?php if (!empty($contestant['contestant_number'])): ?>
                            <span class="badge bg-secondary ms-2">#<?= esc($contestant['contestant_number']) ?></span>
                        <?php endif; ?>
                    </h3>
                    <div class="text-muted mb-3">Event: <?= esc($event['name'] ?? 'N/A') ?></div>

                    <div class="row g-2">
                        <div class="col-12 col-md-6">
                            <div class="mb-2"><strong>Email:</strong> <?= esc($contestant['email'] ?? '—') ?></div>
                            <div class="mb-2"><strong>Phone:</strong> <?= esc($contestant['phone'] ?? '—') ?></div>
                            <div class="mb-2"><strong>Age:</strong> <?= esc($contestant['age'] ?? '—') ?></div>
                            <div class="mb-2"><strong>Hometown:</strong> <?= esc($contestant['hometown'] ?? '—') ?></div>
                            <div class="mb-2"><strong>Gender:</strong> <?= esc($contestant['gender'] ?? '—') ?></div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="mb-2"><strong>Height:</strong> <?= esc($contestant['height'] ?? '—') ?></div>
                            <div class="mb-2"><strong>Weight:</strong> <?= esc($contestant['weight'] ?? '—') ?></div>
                            <div class="mb-2"><strong>Education:</strong> <?= esc($contestant['education'] ?? '—') ?></div>
                            <div class="mb-2"><strong>Occupation:</strong> <?= esc($contestant['occupation'] ?? '—') ?></div>
                        </div>
                    </div>

                    <?php if (!empty($contestant['bio'])): ?>
                        <div class="mt-3">
                            <strong>Bio:</strong>
                            <div class="mt-1 border rounded p-2 bg-light"><?= esc($contestant['bio']) ?></div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
