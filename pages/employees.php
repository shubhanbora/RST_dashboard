<?php
require_once '../config.php';
requireLogin();
$pageTitle = 'Employees';

$employees = readData('employees');

$roles      = ['Frontend','Backend','UI/UX','Tester','Manager','Marketing','Android Developer','iOS Developer'];
$workTypes  = ['Full-time','Part-time','Intern'];

$filterRole = $_GET['role'] ?? '';
$filterWork = $_GET['work_type'] ?? '';
$search     = strtolower(trim($_GET['search'] ?? ''));

$filtered = array_filter($employees, function($e) use ($filterRole, $filterWork, $search) {
    if ($filterRole && $e['role'] !== $filterRole) return false;
    if ($filterWork && $e['work_type'] !== $filterWork) return false;
    if ($search && strpos(strtolower($e['name']), $search) === false) return false;
    return true;
});

include '../includes/layout.php';
?>
<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <form method="GET" class="flex flex-wrap gap-3 items-center">
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search by name..."
            class="px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 w-48">
        <input type="text" name="role" value="<?= htmlspecialchars($filterRole) ?>" placeholder="Filter by role..."
            class="px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 w-40">
        <select name="work_type" class="px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            <option value="">All Work Types</option>
            <?php foreach ($workTypes as $w): ?>
            <option value="<?= $w ?>" <?= $filterWork === $w ? 'selected' : '' ?>><?= $w ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">Filter</button>
        <a href="employees.php" class="px-4 py-2 bg-slate-200 text-slate-700 rounded-lg text-sm hover:bg-slate-300">Reset</a>
    </form>
    <div class="flex gap-2">
        <button onclick="exportEmployeesPDF()" class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm hover:bg-emerald-700 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Export PDF
        </button>
        <a href="employee_add.php" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Employee
        </a>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm" id="empTable">
        <thead class="bg-slate-50 border-b border-slate-200">
            <tr>
                <th class="text-left px-5 py-3 text-slate-600 font-semibold">ID</th>
                <th class="text-left px-5 py-3 text-slate-600 font-semibold">Name</th>
                <th class="text-left px-5 py-3 text-slate-600 font-semibold">Role</th>
                <th class="text-left px-5 py-3 text-slate-600 font-semibold">Work Type</th>
                <th class="text-left px-5 py-3 text-slate-600 font-semibold">Salary</th>
                <th class="text-left px-5 py-3 text-slate-600 font-semibold">Status</th>
                <th class="text-left px-5 py-3 text-slate-600 font-semibold">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            <?php foreach ($filtered as $emp): ?>
            <tr class="hover:bg-slate-50 transition-colors">
                <td class="px-5 py-3 text-slate-500 font-mono text-xs"><?= htmlspecialchars($emp['id']) ?></td>
                <td class="px-5 py-3 font-medium text-slate-800"><?= htmlspecialchars($emp['name']) ?></td>
                <td class="px-5 py-3 text-slate-600"><?= htmlspecialchars($emp['role']) ?></td>
                <td class="px-5 py-3 text-slate-600"><?= htmlspecialchars($emp['work_type']) ?></td>
                <td class="px-5 py-3 text-slate-700">₹<?= number_format($emp['salary'], 2) ?></td>
                <td class="px-5 py-3">
                    <span class="px-2 py-1 rounded-full text-xs font-medium <?= ($emp['salary_status'] ?? '') === 'Paid' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                        <?= htmlspecialchars($emp['salary_status'] ?? 'Unpaid') ?>
                    </span>
                </td>
                <td class="px-5 py-3">
                    <div class="flex gap-2">
                        <a href="employee_detail.php?id=<?= $emp['id'] ?>" class="text-blue-600 hover:text-blue-800 text-xs font-medium">View</a>
                        <a href="../actions/employee_action.php?action=delete&id=<?= $emp['id'] ?>"
                           onclick="return confirm('Delete this employee?')"
                           class="text-red-500 hover:text-red-700 text-xs font-medium">Delete</a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($filtered)): ?>
            <tr><td colspan="7" class="px-5 py-8 text-center text-slate-400">No employees found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
const empData = <?= json_encode(array_values($filtered)) ?>;
function exportEmployeesPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    doc.setFontSize(16);
    doc.text('Employee Report', 14, 15);
    doc.setFontSize(10);
    doc.text('Generated: ' + new Date().toLocaleDateString(), 14, 22);
    doc.autoTable({
        startY: 28,
        head: [['ID','Name','Role','Work Type','Salary','Status']],
        body: empData.map(e => [e.id, e.name, e.role, e.work_type, 'Rs.'+parseFloat(e.salary).toFixed(2), e.salary_status]),
        styles: { fontSize: 9 },
        headStyles: { fillColor: [37, 99, 235] }
    });
    doc.save('employees.pdf');
}
</script>
<?php include '../includes/layout_end.php'; ?>
