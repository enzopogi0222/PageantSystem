<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <link href="<?= base_url('assets/css/app.css') ?>?v=2" rel="stylesheet">
</head>
<body class="login-page">
  <div class="login-wrap">
    <div class="login-title"></span><i class="fas fa-crown" aria-hidden="true"></i><span>Pageant Management System</span></div>
    <div class="login-card">
    <h1>Sign in</h1>
    <p class="login-subtitle">Welcome back. Please enter your credentials.</p>
    <?php if (session()->getFlashdata('error')): ?>
      <div class="login-error"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>
    <form method="post" action="<?= site_url('login') ?>">
      <?= csrf_field() ?>
      <div class="login-form-inner">
        <div class="login-field">
          <label class="login-label" for="identifier">Email or Username</label>
          <input class="login-input" type="text" id="identifier" name="identifier" value="<?= esc(old('identifier')) ?>" autofocus required />
        </div>
        <div class="login-field">
          <label class="login-label" for="password">Password</label>
          <input class="login-input" type="password" id="password" name="password" required />
        </div>
        <button class="login-btn" type="submit">Login</button>
        <p class="login-hint">Default admin: admin / Admin@12345</p>
      </div>
    </form>
    </div>
  </div>
</body>
</html>
