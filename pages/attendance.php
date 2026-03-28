<?php
require_once '../config.php';
requireLogin();
$pageTitle = 'Attendance';

$employees   = readData('employees');
$attendance  = readData('attendance'); // each doc: {id, emp_id, date, status: present/absent/leave, leave_days}
$today       = date('Y-m-d');
$filterDate  = $_GET['date'] ?? $today;

// Build today's attendance map: emp_id => record
$todayMap = [];
foreach ($attendance as $a) {
    if (($a['date'] ?? '') === $filterDate) {
        $todayMap[$a['emp_id']] = $a;
    }
}

// Per employee: count total leaves
$leaveCount = [];
foreach ($attendance as $a) {
    if (($a['status'] ?? '') === 'leave') {
        $empId = $a['emp_id'];
        $leaveCount[$empId] = ($leaveCount[$empId] ?? 0) + (int)($a['leave_days'] ?? 1);
    }
}

include '../includes/layout.php';
?>

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-bold text-slate-800">Attendance</h2>
        <p class="text-sm text-slate-400 mt-0.5">Mark attendance manually or via fingerprint device</p>
    </div>
    <form method="GET" class="flex gap-2 items-center">
        <input type="date" name="date" value="<?= $filterDate ?>"
            class="px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">View</button>
    </form>
</div>

<!-- Summary Cards -->
<?php
$presentCount = count(array_filter($todayMap, fn($a) => $a['status'] === 'present'));
$absentCount  = count(array_filter($todayMap, fn($a) => $a['status'] === 'absent'));
$leaveToday   = count(array_filter($todayMap, fn($a) => $a['status'] === 'leave'));
$notMarked    = count($employees) - count($todayMap);
?>
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl p-4 shadow-sm text-center">
        <p class="text-2xl font-bold text-green-600"><?= $presentCount ?></p>
        <p class="text-xs text-slate-400 mt-1">Present</p>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm text-center">
        <p class="text-2xl font-bold text-red-500"><?= $absentCount ?></p>
        <p class="text-xs text-slate-400 mt-1">Absent</p>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm text-center">
        <p class="text-2xl font-bold text-amber-500"><?= $leaveToday ?></p>
        <p class="text-xs text-slate-400 mt-1">On Leave</p>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm text-center">
        <p class="text-2xl font-bold text-slate-400"><?= $notMarked ?></p>
        <p class="text-xs text-slate-400 mt-1">Not Marked</p>
    </div>
</div>

<!-- Attendance Table -->
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 border-b border-slate-200">
            <tr>
                <th class="text-left px-5 py-3 text-slate-600 font-semibold">Employee</th>
                <th class="text-left px-5 py-3 text-slate-600 font-semibold">Role</th>
                <th class="text-left px-5 py-3 text-slate-600 font-semibold">Status (<?= $filterDate ?>)</th>
                <th class="text-left px-5 py-3 text-slate-600 font-semibold">Total Leaves</th>
                <th class="text-left px-5 py-3 text-slate-600 font-semibold">Mark</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            <?php foreach ($employees as $emp):
                $rec    = $todayMap[$emp['id']] ?? null;
                $status = $rec['status'] ?? 'not_marked';
                $leaves = $leaveCount[$emp['id']] ?? 0;
                $statusStyles = [
                    'present'    => 'bg-green-100 text-green-700',
                    'absent'     => 'bg-red-100 text-red-600',
                    'leave'      => 'bg-amber-100 text-amber-700',
                    'not_marked' => 'bg-slate-100 text-slate-400',
                ];
                $statusLabels = [
                    'present'    => 'Present',
                    'absent'     => 'Absent',
                    'leave'      => 'On Leave',
                    'not_marked' => 'Not Marked',
                ];
            ?>
            <tr class="hover:bg-slate-50">
                <td class="px-5 py-3">
                    <div class="flex items-center gap-3">
                        <?php if (!empty($emp['profile_img'])): ?>
                        <img src="<?= htmlspecialchars($emp['profile_img']) ?>" class="w-8 h-8 rounded-full object-cover">
                        <?php else: ?>
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xs">
                            <?= strtoupper(substr($emp['name'], 0, 1)) ?>
                        </div>
                        <?php endif; ?>
                        <div>
                            <p class="font-medium text-slate-800"><?= htmlspecialchars($emp['name']) ?></p>
                            <p class="text-xs text-slate-400 font-mono"><?= htmlspecialchars($emp['id']) ?></p>
                        </div>
                    </div>
                </td>
                <td class="px-5 py-3 text-slate-600"><?= htmlspecialchars($emp['role'] ?? '') ?></td>
                <td class="px-5 py-3">
                    <span class="px-2.5 py-1 rounded-full text-xs font-medium <?= $statusStyles[$status] ?>">
                        <?= $statusLabels[$status] ?>
                    </span>
                </td>
                <td class="px-5 py-3">
                    <span class="text-sm font-semibold <?= $leaves > 0 ? 'text-amber-600' : 'text-slate-400' ?>">
                        <?= $leaves ?> day<?= $leaves !== 1 ? 's' : '' ?>
                    </span>
                </td>
                <td class="px-5 py-3">
                    <form method="POST" action="../actions/attendance_action.php" class="flex gap-1">
                        <input type="hidden" name="emp_id" value="<?= $emp['id'] ?>">
                        <input type="hidden" name="date"   value="<?= $filterDate ?>">
                        <input type="hidden" name="doc_id" value="<?= htmlspecialchars($rec['id'] ?? '') ?>">
                        <button name="status" value="present"
                            class="px-2.5 py-1 rounded-lg text-xs font-medium <?= $status === 'present' ? 'bg-green-600 text-white' : 'bg-green-50 text-green-700 hover:bg-green-100' ?>">
                            Present
                        </button>
                        <button name="status" value="absent"
                            class="px-2.5 py-1 rounded-lg text-xs font-medium <?= $status === 'absent' ? 'bg-red-500 text-white' : 'bg-red-50 text-red-600 hover:bg-red-100' ?>">
                            Absent
                        </button>
                        <button name="status" value="leave"
                            class="px-2.5 py-1 rounded-lg text-xs font-medium <?= $status === 'leave' ? 'bg-amber-500 text-white' : 'bg-amber-50 text-amber-700 hover:bg-amber-100' ?>">
                            Leave
                        </button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($employees)): ?>
            <tr><td colspan="5" class="px-5 py-8 text-center text-slate-400">No employees found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Fingerprint API Info -->
<div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-4">
    <p class="text-sm font-semibold text-blue-800">Fingerprint Device Setup</p>
    <p class="text-xs text-blue-600 mt-1">
        Configure your fingerprint device to POST to <code class="bg-blue-100 px-1 rounded">actions/attendance_action.php</code> with fields:
        <code class="bg-blue-100 px-1 rounded">emp_id</code>, <code class="bg-blue-100 px-1 rounded">date</code>,
        <code class="bg-blue-100 px-1 rounded">status=present</code>, and <code class="bg-blue-100 px-1 rounded">api_key=RST_FINGERPRINT_KEY</code>.
        Attendance will update in Firebase automatically.
    </p>
</div>

<?php include '../includes/layout_end.php'; ?>
