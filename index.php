<?php
require_once 'config.php';

if (isLoggedIn()) {
    redirect('pages/dashboard.php');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';
    $result = firebaseLogin($email, $pass);
    if ($result === true) {
        redirect('pages/dashboard.php');
    } else {
        $error = $result;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login</title>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body {
    min-height: 100vh;
    display: flex;
    font-family: 'Segoe UI', sans-serif;
    background: #f1f5f9;
  }

  /* Left Panel */
  .left-panel {
    width: 55%;
    background: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 40%, #2563eb 100%);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 60px 40px;
    position: relative;
    overflow: hidden;
  }
  .left-panel::before {
    content: '';
    position: absolute;
    width: 400px; height: 400px;
    background: rgba(255,255,255,0.05);
    border-radius: 50%;
    top: -100px; left: -100px;
  }
  .left-panel::after {
    content: '';
    position: absolute;
    width: 300px; height: 300px;
    background: rgba(255,255,255,0.05);
    border-radius: 50%;
    bottom: -80px; right: -80px;
  }
  .brand-logo {
    width: 90px; height: 90px;
    background: rgba(255,255,255,0.95);
    border-radius: 20px;
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 28px;
    padding: 8px;
  }
  .brand-logo svg { width: 36px; height: 36px; }
  .left-panel h1 {
    color: #fff;
    font-size: 32px;
    font-weight: 800;
    text-align: center;
    margin-bottom: 12px;
    letter-spacing: -0.5px;
  }
  .left-panel p {
    color: rgba(255,255,255,0.65);
    font-size: 15px;
    text-align: center;
    max-width: 320px;
    line-height: 1.6;
  }
  .features {
    margin-top: 48px;
    display: flex;
    flex-direction: column;
    gap: 16px;
    width: 100%;
    max-width: 320px;
    position: relative;
    z-index: 1;
  }
  .feature-item {
    display: flex;
    align-items: center;
    gap: 14px;
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.12);
    border-radius: 12px;
    padding: 14px 18px;
  }
  .feature-icon {
    width: 36px; height: 36px;
    background: rgba(255,255,255,0.15);
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
  }
  .feature-icon svg { width: 18px; height: 18px; }
  .feature-text p { color: #fff; font-size: 13px; font-weight: 600; }
  .feature-text span { color: rgba(255,255,255,0.55); font-size: 12px; }

  /* Right Panel */
  .right-panel {
    width: 45%;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px;
    background: #fff;
  }
  .login-box {
    width: 100%;
    max-width: 400px;
  }
  .login-box h2 {
    font-size: 26px;
    font-weight: 800;
    color: #1e293b;
    margin-bottom: 6px;
  }
  .login-box .subtitle {
    color: #64748b;
    font-size: 14px;
    margin-bottom: 36px;
  }
  .error-box {
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: #dc2626;
    padding: 12px 16px;
    border-radius: 10px;
    font-size: 13px;
    margin-bottom: 20px;
  }
  .form-group { margin-bottom: 20px; }
  .form-group label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 6px;
  }
  .form-group input {
    width: 100%;
    padding: 12px 16px;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    font-size: 14px;
    color: #1e293b;
    outline: none;
    transition: border-color 0.2s;
    background: #f8fafc;
  }
  .form-group input:focus {
    border-color: #2563eb;
    background: #fff;
  }
  .submit-btn {
    width: 100%;
    padding: 13px;
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    color: #fff;
    border: none;
    border-radius: 10px;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    transition: opacity 0.2s, transform 0.1s;
    margin-top: 8px;
  }
  .submit-btn:hover { opacity: 0.92; transform: translateY(-1px); }
  .submit-btn:active { transform: translateY(0); }
  .login-footer {
    margin-top: 28px;
    text-align: center;
    color: #94a3b8;
    font-size: 12px;
  }

  @media (max-width: 768px) {
    body { flex-direction: column; }
    .left-panel { width: 100%; padding: 40px 24px; min-height: auto; }
    .right-panel { width: 100%; padding: 32px 24px; }
    .features { display: none; }
  }
</style>
</head>
<body>

  <!-- Left Panel -->
  <div class="left-panel">
    <div class="brand-logo">
      <img src="assets/rst-logo.png" alt="RST Logo" style="width:64px;height:64px;object-fit:contain;">
    </div>
    <h1>RST Admin Panel</h1>
    <p>Complete management system for employees, projects and clients.</p>

    <div class="features">
      <div class="feature-item">
        <div class="feature-icon">
          <svg fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
          </svg>
        </div>
        <div class="feature-text">
          <p>Employee Management</p>
          <span>Track roles, salary & work type</span>
        </div>
      </div>
      <div class="feature-item">
        <div class="feature-icon">
          <svg fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
          </svg>
        </div>
        <div class="feature-text">
          <p>Project Tracking</p>
          <span>Monitor status & deliverables</span>
        </div>
      </div>
      <div class="feature-item">
        <div class="feature-icon">
          <svg fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
          </svg>
        </div>
        <div class="feature-text">
          <p>Client & Payments</p>
          <span>Manage agreements & balances</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Right Panel -->
  <div class="right-panel">
    <div class="login-box">
      <h2>Welcome back</h2>
      <p class="subtitle">Sign in to your admin account</p>

      <?php if ($error): ?>
      <div class="error-box"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form method="POST">
        <div class="form-group">
          <label>Email Address</label>
          <input type="email" name="email" required placeholder="admin@example.com" autocomplete="email">
        </div>
        <div class="form-group">
          <label>Password</label>
          <div style="position:relative;">
            <input type="password" name="password" id="passwordInput" required placeholder="••••••••" autocomplete="current-password" style="padding-right:44px;">
            <button type="button" onclick="togglePassword()" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;padding:0;color:#94a3b8;" tabindex="-1">
              <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
              </svg>
              <svg id="eyeOffIcon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:none;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
              </svg>
            </button>
          </div>
        </div>
        <button type="submit" class="submit-btn">Sign In &rarr;</button>
      </form>

      <div class="login-footer">
        &copy; <?= date('Y') ?> RST Admin Panel. All rights reserved.
      </div>
    </div>
  </div>

<script>
function togglePassword() {
  const input = document.getElementById('passwordInput');
  const eyeOn  = document.getElementById('eyeIcon');
  const eyeOff = document.getElementById('eyeOffIcon');
  if (input.type === 'password') {
    input.type = 'text';
    eyeOn.style.display  = 'none';
    eyeOff.style.display = 'block';
  } else {
    input.type = 'password';
    eyeOn.style.display  = 'block';
    eyeOff.style.display = 'none';
  }
}
</script>
</body>
</html>
