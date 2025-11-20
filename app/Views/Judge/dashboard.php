<?= $this->extend('layouts/template') ?>

<?= $this->section('content') ?>
<div class="container py-3">
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= esc($error) ?></div>
    <?php endif; ?>
    <?php if (!empty($message)): ?>
        <div class="alert alert-success"><?= esc($message) ?></div>
    <?php endif; ?>

    <div class="mb-4">
        <h2 class="mb-1">Welcome, Judge</h2>
        <div class="text-muted">Event: <?= esc($event['name'] ?? 'N/A') ?></div>
    </div>

    <div class="d-flex justify-content-end mb-2">
        <a href="<?= esc(base_url('judge/contestants')) ?>" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-users me-1"></i> View Contestants
        </a>
    </div>

    <div class="row g-3">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <strong>Rounds & Criteria</strong>
                </div>
                <div class="card-body">
                    <?php if (empty($rounds)): ?>
                        <div class="text-muted">No rounds available.</div>
                    <?php else: ?>
                        <div class="accordion" id="roundsAccordion">
                            <?php foreach ($rounds as $idx => $r): ?>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading<?= $idx ?>">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $idx ?>">
                                            <?= esc($r['name'] ?? 'Round') ?>
                                        </button>
                                    </h2>
                                    <div id="collapse<?= $idx ?>" class="accordion-collapse collapse" data-bs-parent="#roundsAccordion">
                                        <div class="accordion-body">
                                            <div class="mb-2 small text-muted">Max Score: <?= esc($r['max_score'] ?? '-') ?></div>
                                            <div class="mb-2">Criteria:</div>
                                            <ul class="mb-0">
                                                <?php $cr = $criteriaByRound[$r['id']] ?? []; ?>
                                                <?php if (empty($cr)): ?>
                                                    <li class="text-muted">No criteria configured.</li>
                                                <?php else: ?>
                                                    <?php foreach ($cr as $c): ?>
                                                        <li><?= esc($c['name']) ?> (Max <?= esc($c['max_score'] ?? '-') ?>, Weight <?= esc($c['weight'] ?? '-') ?>%)</li>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <div class="alert alert-info mb-0">
            Voting UI can go here (by round), limited to your assigned event.
        </div>
    </div>
</div>
<?= $this->endSection() ?>
