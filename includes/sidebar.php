<?php
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$navItems = [
    ['href' => 'dashboard.php',  'label' => 'Dashboard', 'page' => 'dashboard',  'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
    ['href' => 'employees.php',  'label' => 'Employees', 'page' => 'employees',  'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
    ['href' => 'projects.php',   'label' => 'Projects',  'page' => 'projects',   'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
    ['href' => 'clients.php',    'label' => 'Clients',    'page' => 'clients',    'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
    ['href' => 'attendance.php', 'label' => 'Attendance', 'page' => 'attendance', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
];
?>
<aside style="position:fixed;top:0;left:0;height:100vh;width:256px;background:#1e293b;display:flex;flex-direction:column;z-index:30;">

  <div style="padding:20px 16px;border-bottom:1px solid #334155;flex-shrink:0;">
    <div style="display:flex;align-items:center;gap:12px;">
      <div style="width:42px;height:42px;flex-shrink:0;">
        <img src="../assets/rst-logo.png" style="width:42px;height:42px;object-fit:contain;">
      </div>
      <div>
        <p style="color:#ffffff;font-weight:700;font-size:14px;margin:0;">RST Admin</p>
        <p style="color:#94a3b8;font-size:11px;margin:0;">Management System</p>
      </div>
    </div>
  </div>

  <nav style="flex:1;padding:12px 10px;">
    <?php foreach ($navItems as $item):
      $isActive = $currentPage === $item['page'];
      $bg = $isActive ? '#2563eb' : 'transparent';
    ?>
    <a href="<?= $item['href'] ?>"
       style="display:flex;align-items:center;gap:12px;padding:10px 14px;border-radius:8px;text-decoration:none;color:#ffffff;font-size:14px;font-weight:500;margin-bottom:4px;background:<?= $bg ?>;"
       onmouseover="this.style.background='<?= $isActive ? '#1d4ed8' : '#334155' ?>'"
       onmouseout="this.style.background='<?= $bg ?>'">
      <svg width="18" height="18" fill="none" stroke="#ffffff" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;">
        <path stroke-linecap="round" stroke-linejoin="round" d="<?= $item['icon'] ?>"/>
      </svg>
      <span style="color:#ffffff;"><?= $item['label'] ?></span>
    </a>
    <?php endforeach; ?>
  </nav>

  <div style="padding:10px;border-top:1px solid #334155;flex-shrink:0;">
    <a href="../actions/logout.php"
       style="display:flex;align-items:center;gap:12px;padding:10px 14px;border-radius:8px;text-decoration:none;color:#ffffff;font-size:14px;font-weight:500;"
       onmouseover="this.style.background='#dc2626'"
       onmouseout="this.style.background='transparent'">
      <svg width="18" height="18" fill="none" stroke="#ffffff" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;">
        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
      </svg>
      <span style="color:#ffffff;">Logout</span>
    </a>
  </div>
</aside>
