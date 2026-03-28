<?php
require_once '../config.php';
requireLogin();
$pageTitle = 'Dashboard';

$employees = readData('employees');
$projects  = readData('projects');
$clients   = readData('clients');

$totalSalary   = array_sum(array_column($employees, 'salary'));
$paidClients   = count(array_filter($clients, fn($c) => ($c['paid_amount'] ?? 0) >= ($c['total_amount'] ?? 1)));
$ongoingProj   = count(array_filter($projects, fn($p) => ($p['status'] ?? '') === 'Ongoing'));

include '../includes/layout.php';
?>
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
    <?php
    $cards = [
        ['label'=>'Total Employees', 'value'=>count($employees), 'bg'=>'#3b82f6', 'icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
        ['label'=>'Total Projects',  'value'=>count($projects),  'bg'=>'#10b981', 'icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
        ['label'=>'Total Clients',   'value'=>count($clients),   'bg'=>'#f59e0b', 'icon'=>'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
        ['label'=>'Ongoing Projects','value'=>$ongoingProj,      'bg'=>'#ef4444', 'icon'=>'M13 10V3L4 14h7v7l9-11h-7z'],
    ];
    foreach ($cards as $card): ?>
    <div class="bg-white rounded-xl shadow-sm p-5 flex items-center gap-4">
        <div style="width:48px;height:48px;background:<?= $card['bg'] ?>;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg width="24" height="24" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="<?= $card['icon'] ?>"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-slate-800"><?= $card['value'] ?></p>
            <p class="text-sm text-slate-500"><?= $card['label'] ?></p>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Employees -->
    <div class="bg-white rounded-xl shadow-sm p-5">
        <h2 class="font-semibold text-slate-800 mb-4">Recent Employees</h2>
        <div class="space-y-3">
            <?php foreach (array_slice(array_reverse($employees), 0, 5) as $emp): ?>
            <div class="flex items-center justify-between py-2 border-b border-slate-50">
                <div>
                    <p class="text-sm font-medium text-slate-700"><?= htmlspecialchars($emp['name']) ?></p>
                    <p class="text-xs text-slate-400"><?= htmlspecialchars($emp['role']) ?> &bull; <?= htmlspecialchars($emp['work_type']) ?></p>
                </div>
                <span class="text-xs px-2 py-1 rounded-full <?= ($emp['salary_status'] ?? '') === 'Paid' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                    <?= htmlspecialchars($emp['salary_status'] ?? 'Unpaid') ?>
                </span>
            </div>
            <?php endforeach; ?>
            <?php if (empty($employees)): ?><p class="text-sm text-slate-400">No employees yet.</p><?php endif; ?>
        </div>
    </div>
    <!-- Recent Projects -->
    <div class="bg-white rounded-xl shadow-sm p-5">
        <h2 class="font-semibold text-slate-800 mb-4">Recent Projects</h2>
        <div class="space-y-3">
            <?php foreach (array_slice(array_reverse($projects), 0, 5) as $proj): ?>
            <div class="flex items-center justify-between py-2 border-b border-slate-50">
                <div>
                    <p class="text-sm font-medium text-slate-700"><?= htmlspecialchars($proj['name']) ?></p>
                    <p class="text-xs text-slate-400"><?= htmlspecialchars($proj['type'] ?? '') ?></p>
                </div>
                <span class="text-xs px-2 py-1 rounded-full <?= ($proj['status'] ?? '') === 'Completed' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' ?>">
                    <?= htmlspecialchars($proj['status'] ?? '') ?>
                </span>
            </div>
            <?php endforeach; ?>
            <?php if (empty($projects)): ?><p class="text-sm text-slate-400">No projects yet.</p><?php endif; ?>
        </div>
    </div>
</div>
<?php include '../includes/layout_end.php'; ?>
