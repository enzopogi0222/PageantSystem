<?php
?>
<?php
  
    $currentUrl = current_url();
    $menuItems = [
        'dashboard' => [
            'url'  => 'dashboard',
            'icon' => 'tachometer-alt',
            'text' => 'Dashboard',
        ],
        'contestants' => [
            'url'  => 'contestant',
            'icon' => 'users',
            'text' => 'Contestants',
        ],
        'judges' => [
            'url'  => 'judges',
            'icon' => 'gavel',
            'text' => 'Judges',
        ],
        'rounds' => [
            'url'  => 'rounds',
            'icon' => 'trophy',
            'text' => 'Rounds',
        ],
        'results' => [
            'url'  => 'results',
            'icon' => 'chart-line',
            'text' => 'Results',
        ],
    ];
?>
<div class="floating-nav-wrap mb-3">
  <!-- Title above the header bar -->
  <div class="floating-title"><span class="title-pill"><i class="fas fa-crown"></i> <?= htmlspecialchars($system_name ?? 'Pageant Management System') ?></span></div>
  <nav class="navbar navbar-expand-lg navbar-dark header-gradient floating-nav">
  <div class="container-fluid">
    <!-- Mobile brand (hidden on lg and up to prevent duplication) -->
    <a class="navbar-brand fw-semibold d-lg-none" href="/dashboard">
      <i class="fas fa-crown me-2"></i>
      <?php echo htmlspecialchars($system_name ?? 'Pageant Management System'); ?>
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="mainNavbar">
      <!-- Left placeholder to perfectly center middle nav on lg+ (mirrors dropdown width) -->
      <ul class="navbar-nav d-none d-lg-flex me-auto" aria-hidden="true" style="visibility:hidden">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-user-circle me-1"></i> <?= isset($user_greeting) && $user_greeting ? esc($user_greeting) : 'Menu' ?>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="<?= base_url('admin') ?>"><i class="fas fa-home me-2"></i>Admin Home</a></li>
            <li><a class="dropdown-item" href="<?= base_url('events/create') ?>"><i class="fas fa-calendar-plus me-2"></i>Add Event</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Settings</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="<?= isset($logout_url) ? esc($logout_url) : base_url('logout') ?>"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
          </ul>
        </li>
      </ul>

      <?php if (empty($hide_center_nav) || !$hide_center_nav): ?>
      <ul class="navbar-nav mx-auto mb-2 mb-lg-0 justify-content-center">
        <?php foreach ($menuItems as $key => $item): 
            $isCurrentPage = strpos($currentUrl, $item['url']) !== false;
            $isHomeDashboard = uri_string() === '' && $key === 'dashboard';
            $activeClass = ($isCurrentPage || $isHomeDashboard) ? 'active' : '';
            $href = base_url($item['url']);
        ?>
          <li class="nav-item">
            <a class="nav-link <?= $activeClass ?>" href="<?= $href ?>">
              <i class="fas fa-<?= $item['icon'] ?> me-1"></i> <?= $item['text'] ?>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>
      <?php else: ?>
        <?php if (!empty($user_greeting)): ?>
          <div class="navbar-text mx-auto text-white fw-semibold fs-4"><?= esc($user_greeting) ?></div>
        <?php endif; ?>
      <?php endif; ?>

      <div class="navbar-divider d-none d-lg-block"></div>
      <ul class="navbar-nav ms-auto">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-user-circle me-1"></i> Menu
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="<?= base_url('admin') ?>"><i class="fas fa-home me-2"></i>Admin Home</a></li>
            <li><a class="dropdown-item" href="<?= base_url('events/create') ?>"><i class="fas fa-calendar-plus me-2"></i>Add Event</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#systemSettingsModal"><i class="fas fa-cog me-2"></i>Settings</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="<?= base_url('logout') ?>"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
</div>
