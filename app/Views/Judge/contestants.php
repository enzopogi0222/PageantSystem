<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-1">
        <h2 class="mb-0"><i class="fas fa-users me-2"></i> Contestants</h2>
        <a href="<?= esc(base_url('judge')) ?>" class="btn btn-outline-secondary btn-sm"><i class="fa fa-arrow-left me-1"></i> Back</a>
    </div>
    <p class="text-muted mb-4">Read-only list of contestants for <?= esc($event['name'] ?? 'Event') ?>.</p>

    <div class="card card-soft">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">All Contestants (<?= count($contestants) ?>)</h5>
        </div>
        <div class="card-body">
            <?php if (empty($contestants)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No contestants yet</h4>
                </div>
            <?php else: ?>
                <div class="row g-3">
                    <?php foreach ($contestants as $c): ?>
                        <?php $photo = $c['photo_url'] ?? null; ?>
                        <?php $status = $c['status'] ?? 'registered'; ?>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
                            <div class="card h-100 shadow-sm">
                                <div class="card-img-top d-flex align-items-center justify-content-center" style="height: 200px; overflow: hidden; background: #f8f9fa; border-top-left-radius: .5rem; border-top-right-radius: .5rem;">
                                    <?php if (!empty($photo)): ?>
                                        <img src="<?= esc($photo) ?>" alt="Photo" style="max-height: 100%; max-width: 100%; object-fit: contain; object-position: center; display:block;"
                                             onerror="this.style.display='none'; const p=this.nextElementSibling; if(p) p.style.display='flex';">
                                        <div class="d-flex align-items-center justify-content-center" style="height: 100%; width: 100%; display:none;">
                                            <i class="fas fa-user fa-3x text-muted"></i>
                                        </div>
                                    <?php else: ?>
                                        <div class="d-flex align-items-center justify-content-center" style="height: 100%; width: 100%;">
                                            <i class="fas fa-user fa-3x text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <span class="badge bg-secondary">#<?= esc($c['contestant_number'] ?? '-') ?></span>
                                        <span class="badge status-badge bg-<?= $status === 'qualified' ? 'success' : ($status === 'registered' ? 'warning' : 'danger') ?>"><?= ucfirst($status) ?></span>
                                    </div>
                                    <h6 class="card-title mb-1">
                                        <a href="<?= esc(base_url('judge/contestants/'.($c['id'] ?? 0))) ?>" class="text-decoration-none">
                                            <?= esc(($c['first_name'] ?? '') . ' ' . ($c['last_name'] ?? '')) ?>
                                        </a>
                                    </h6>
                                    <div class="text-muted small">Age: <?= isset($c['age']) && $c['age'] !== '' ? esc($c['age']) : '-' ?></div>
                                    <div class="text-muted small">Hometown: <?= esc($c['hometown'] ?? '-') ?></div>
                                </div>
                                <div class="card-footer bg-white border-0 d-flex justify-content-between">
                                    <a class="btn btn-outline-primary btn-sm" href="<?= esc(base_url('judge/contestants/'.($c['id'] ?? 0))) ?>">
                                        <i class="fas fa-eye me-1"></i> View Info
                                    </a>
                                    <!-- No edit/delete/add for judges -->
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
