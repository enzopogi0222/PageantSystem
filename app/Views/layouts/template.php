<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
        // Resolve system name globally using the latest event if not explicitly provided
        if (!isset($system_name) || empty($system_name)) {
            try {
                $em = new \App\Models\EventModel();
                // Prefer active event if present
                $active = $em->where('is_active', 1)->orderBy('id', 'DESC')->first();
                if ($active && !empty($active['name'])) {
                    $system_name = $active['name'];
                } else {
                    $latest = $em->orderBy('id', 'DESC')->first();
                    if ($latest && !empty($latest['name'])) {
                        $system_name = $latest['name'];
                    }
                }
            } catch (\Throwable $e) {
                // ignore if table not yet migrated
            }
        }
    ?>
    <title><?= esc($title ?? ($system_name ?? 'Pageant Management System')) ?></title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Google Fonts (Inter) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- App stylesheet -->
    <link href="<?= base_url('assets/css/app.css') ?>?v=1" rel="stylesheet">

    <style>
        :root {
            --primary-color: <?= esc($primary_color ?? '#6f42c1') ?>;
            --secondary-color: <?= esc($secondary_color ?? '#495057') ?>;
            --accent-color: <?= esc($accent_color ?? '#28a745') ?>;
        }
        html, body { font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, sans-serif; }
        .main-wrapper { padding: 0 12px; }
        .main-content { padding-bottom: 32px; }

        /* Fallbacks if external CSS fails to load */
        .floating-nav-wrap { position: sticky; top: 12px; z-index: 1030; padding: 0 12px; margin-bottom: 24px; }
        .floating-title { width: calc(100% - 24px); margin: 0 auto 8px auto; text-align: center; }
        .floating-title .title-pill { display: inline-flex; align-items: center; gap: .5rem; padding: .5rem 1rem; border-radius: 999px; color: #fff; background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); box-shadow: 0 8px 24px rgba(0,0,0,.12); border: 1px solid rgba(255,255,255,0.22); font-weight: 700; }
        .header-gradient { background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); }
        .floating-nav { border-radius: 16px; box-shadow: 0 12px 30px rgba(0,0,0,0.15); width: calc(100% - 24px); margin: 0 auto; background: linear-gradient(135deg, rgba(111,66,193,0.85) 0%, rgba(73,80,87,0.85) 100%); border: 1px solid rgba(255,255,255,0.18); }
        .navbar .nav-link { color: rgba(255,255,255,0.92); padding: .5rem .9rem; border-radius: 10px; }
        .navbar .nav-link.active, .navbar .nav-link:hover { color: #fff; background-color: rgba(255,255,255,0.18); }
        .stat-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 1rem; }
        @media (max-width: 991.98px) { .stat-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
        @media (max-width: 575.98px) { .stat-grid { grid-template-columns: 1fr; } }
        .stat-item { display: flex; align-items: center; gap: .9rem; padding: 1rem; border-radius: 12px; background: #fff; box-shadow: 0 6px 16px rgba(0,0,0,.05); }
        .stat-icon { width: 44px; height: 44px; border-radius: 12px; display: grid; place-items: center; color: #fff; font-size: 1.1rem; background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); }
    </style>
</head>
<body>

    <!-- Header (floating, already styled inside partial) -->
    <?php if (empty($hide_header) || !$hide_header): ?>
    <?= view('partials/header', [
        'system_name'   => $system_name ?? 'Pageant Management System',
        'page_title'    => $page_title ?? null,
        'user_greeting' => $user_greeting ?? null,
        'hide_center_nav' => $hide_center_nav ?? false,
        'logout_url'    => $logout_url ?? null,
    ]) ?>
    <?php endif; ?>

    <!-- Main Content Wrapper -->
    <div class="main-wrapper">
        <main class="main-content">
            <?= $this->renderSection('content') ?>
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <?= $this->renderSection('scripts') ?>
</body>
</html>
