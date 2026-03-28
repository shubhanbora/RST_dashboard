<?php
require_once '../config.php';
requireLogin();

$id        = $_GET['id'] ?? '';
$employees = readData('employees');
$emp       = null;
foreach ($employees as $e) { if ($e['id'] === $id) { $emp = $e; break; } }
if (!$emp) { redirect('employees.php'); }

$pageTitle = 'Employee: ' . htmlspecialchars($emp['name']);
$roles     = ['Frontend','Backend','UI/UX','Tester','Manager','Marketing','Android Developer','iOS Developer'];
$workTypes = ['Full-time','Part-time','Intern'];
$mode      = $_GET['mode'] ?? 'view'; // view or edit
$saved     = isset($_GET['saved']);

include '../includes/layout.php';
?>

<?php if ($saved): ?>
<div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">Changes saved successfully.</div>
<?php endif; ?>

<!-- Breadcrumb -->
<nav class="text-sm text-slate-400 mb-5">
    <a href="dashboard.php" class="hover:text-blue-600">Home</a> &rsaquo;
    <a href="employees.php" class="hover:text-blue-600">Employees</a> &rsaquo;
    <span class="text-slate-600"><?= htmlspecialchars($emp['name']) ?></span>
</nav>

<h1 class="text-2xl font-bold text-slate-800 mb-6">Employee Profile: <?= htmlspecialchars($emp['name']) ?></h1>

<div class="flex gap-6 items-start">

    <!-- Left Card: Photo + Quick Links -->
    <div class="w-56 flex-shrink-0">
        <div class="bg-white rounded-xl shadow-sm p-5 text-center">
            <!-- Profile photo with quick upload -->
            <form method="POST" action="../actions/employee_action.php" enctype="multipart/form-data" id="photoForm">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" value="<?= htmlspecialchars($emp['id']) ?>">
                <div class="relative w-28 h-28 mx-auto cursor-pointer" onclick="document.getElementById('quickPhoto').click()">
                    <?php if (!empty($emp['profile_img'])): ?>
                    <img id="photoPreview" src="<?= htmlspecialchars($emp['profile_img']) ?>" class="w-28 h-28 rounded-full object-cover border-4 border-blue-100">
                    <?php else: ?>
                    <div id="photoPreview" class="w-28 h-28 rounded-full bg-blue-100 flex items-center justify-center">
                        <svg class="w-14 h-14 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <?php endif; ?>
                    <div class="absolute bottom-0 right-0 w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center border-2 border-white">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                </div>
                <input type="file" id="quickPhoto" name="profile_img" accept="image/*" class="hidden" onchange="previewAndSubmit(this)">
            </form>
            <p class="mt-3 font-semibold text-slate-800"><?= htmlspecialchars($emp['name']) ?></p>
            <p class="text-xs text-slate-400 mt-0.5"><?= htmlspecialchars($emp['role']) ?></p>

            <div class="mt-5 text-left border-t border-slate-100 pt-4">
                <p class="text-xs font-semibold text-slate-600 mb-2">Quick links</p>
                <a href="?id=<?= $emp['id'] ?>&mode=edit" class="flex items-center gap-2 text-sm text-slate-600 hover:text-blue-600 py-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Edit Profile
                </a>
                <?php if (!empty($emp['aadhar_img']) || !empty($emp['pan_img'])): ?>
                <a href="#documents" class="flex items-center gap-2 text-sm text-slate-600 hover:text-blue-600 py-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    View Documents
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Right: Profile Info / Edit Form -->
    <div class="flex-1">
        <?php if ($mode === 'edit'): ?>
        <!-- ── EDIT FORM ── -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="font-semibold text-slate-800">Edit Profile</h2>
                <a href="?id=<?= $emp['id'] ?>" class="text-sm text-slate-400 hover:text-slate-600">← Cancel</a>
            </div>
            <form method="POST" action="../actions/employee_action.php" enctype="multipart/form-data" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" value="<?= htmlspecialchars($emp['id']) ?>">

                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Full Name</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($emp['name']) ?>" required class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($emp['email'] ?? '') ?>" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Contact</label>
                    <input type="text" name="contact" value="<?= htmlspecialchars($emp['contact'] ?? '') ?>" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Role</label>
                    <input type="text" name="role" value="<?= htmlspecialchars($emp['role'] ?? '') ?>" list="roleList" placeholder="e.g. Backend Developer"
                        class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <datalist id="roleList">
                        <option value="Frontend Developer">
                        <option value="Backend Developer">
                        <option value="Full Stack Developer">
                        <option value="Android Developer">
                        <option value="iOS Developer">
                        <option value="UI/UX Designer">
                        <option value="Tester / QA">
                        <option value="Project Manager">
                        <option value="Marketing">
                        <option value="DevOps">
                        <option value="Intern">
                    </datalist>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Work Type</label>
                    <select name="work_type" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <?php foreach ($workTypes as $w): ?>
                        <option value="<?= $w ?>" <?= ($emp['work_type'] ?? '') === $w ? 'selected' : '' ?>><?= $w ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Department</label>
                    <input type="text" name="department" value="<?= htmlspecialchars($emp['department'] ?? '') ?>" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Manager</label>
                    <input type="text" name="manager" value="<?= htmlspecialchars($emp['manager'] ?? '') ?>" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Salary (₹)</label>
                    <input type="number" name="salary" value="<?= $emp['salary'] ?>" min="0" step="0.01" required class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Salary Status</label>
                    <select name="salary_status" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <option value="Unpaid" <?= ($emp['salary_status'] ?? '') === 'Unpaid' ? 'selected' : '' ?>>Unpaid</option>
                        <option value="Paid"   <?= ($emp['salary_status'] ?? '') === 'Paid'   ? 'selected' : '' ?>>Paid</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Joining Date</label>
                    <input type="date" name="joining_date" value="<?= htmlspecialchars($emp['joining_date'] ?? $emp['created_at'] ?? '') ?>" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Aadhar No.</label>
                    <input type="text" name="aadhar_no" value="<?= htmlspecialchars($emp['aadhar_no'] ?? '') ?>"
                        pattern="\d{12}" maxlength="12" inputmode="numeric"
                        placeholder="12 digit number"
                        oninput="this.value=this.value.replace(/\D/g,'')"
                        class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">PAN No.</label>
                    <input type="text" name="pan_no" value="<?= htmlspecialchars($emp['pan_no'] ?? '') ?>"
                        pattern="[A-Z]{5}[0-9]{4}[A-Z]{1}" maxlength="10"
                        placeholder="e.g. ABCDE1234F"
                        oninput="this.value=this.value.toUpperCase().replace(/[^A-Z0-9]/g,'')"
                        class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-medium text-slate-600 mb-1">Address</label>
                    <input type="text" name="address" value="<?= htmlspecialchars($emp['address'] ?? '') ?>" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>

                <!-- Documents -->
                <div class="sm:col-span-2 border-t border-slate-100 pt-4">
                    <p class="text-xs font-semibold text-slate-600 mb-3">Documents</p>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1">Profile Photo</label>
                            <input type="file" name="profile_img" accept="image/*" class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:bg-blue-50 file:text-blue-600">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1">Aadhar Card</label>
                            <input type="file" name="aadhar_img" accept="image/*,.pdf" class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:bg-amber-50 file:text-amber-600">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1">PAN Card</label>
                            <input type="file" name="pan_img" accept="image/*,.pdf" class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:bg-emerald-50 file:text-emerald-600">
                        </div>
                    </div>
                </div>

                <div class="sm:col-span-2 flex gap-3 pt-2">
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium">Save Changes</button>
                    <a href="?id=<?= $emp['id'] ?>" class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-sm font-medium">Cancel</a>
                    <a href="../actions/employee_action.php?action=delete&id=<?= $emp['id'] ?>"
                       onclick="return confirm('Delete this employee?')"
                       class="ml-auto px-6 py-2.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg text-sm font-medium">Delete Employee</a>
                </div>
            </form>
        </div>

        <?php else: ?>
        <!-- ── VIEW MODE ── -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <!-- Top bar -->
            <div class="flex items-center justify-between mb-5 pb-4 border-b border-slate-100">
                <div>
                    <p class="text-xs text-slate-400 mb-0.5">Employee ID</p>
                    <p class="font-mono font-semibold text-slate-700"><?= htmlspecialchars($emp['id']) ?></p>
                </div>
                <div class="flex gap-2">
                    <a href="?id=<?= $emp['id'] ?>&mode=edit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium">Update Profile</a>
                    <button onclick="exportEmpPDF()" class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-lg text-sm font-medium">Download PDF</button>
                    <a href="../actions/employee_action.php?action=delete&id=<?= $emp['id'] ?>"
                       onclick="return confirm('Delete this employee?')"
                       class="px-4 py-2 border border-red-200 hover:bg-red-50 text-red-500 rounded-lg text-sm font-medium">Deactivate Account</a>
                </div>
            </div>

            <!-- Info Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-10 gap-y-4 mb-6">
                <?php
                function infoRow($icon, $label, $value) {
                    if (empty($value)) return;
                    echo '<div class="flex items-start gap-3">';
                    echo '<div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">';
                    echo '<svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="' . $icon . '"/></svg>';
                    echo '</div>';
                    echo '<div><p class="text-xs text-slate-400">' . $label . '</p>';
                    echo '<p class="text-sm font-semibold text-slate-800">' . htmlspecialchars($value) . '</p></div>';
                    echo '</div>';
                }
                infoRow('M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'Full Name', $emp['name']);
                infoRow('M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'Role', $emp['role']);
                infoRow('M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'Monthly Salary', '₹' . number_format($emp['salary'], 2));
                ?>
                <!-- Work Type badge -->
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400">Work Type</p>
                        <span class="inline-block mt-0.5 px-2.5 py-0.5 bg-blue-100 text-blue-700 rounded-full text-xs font-medium"><?= htmlspecialchars($emp['work_type']) ?></span>
                    </div>
                </div>
                <!-- Salary Status badge -->
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400">Salary Status</p>
                        <?php $paid = ($emp['salary_status'] ?? '') === 'Paid'; ?>
                        <span class="inline-block mt-0.5 px-2.5 py-0.5 rounded-full text-xs font-medium <?= $paid ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' ?>">
                            <?= htmlspecialchars($emp['salary_status'] ?? 'Unpaid') ?>
                        </span>
                    </div>
                </div>
                <?php
                infoRow('M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'Email', $emp['email'] ?? '');
                infoRow('M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z', 'Contact', $emp['contact'] ?? '');
                infoRow('M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0', 'Aadhar No.', $emp['aadhar_no'] ?? '');
                infoRow('M15 9a2 2 0 10-4 0v5a2 2 0 01-2 2h6m-6-4h4m8 0a9 9 0 11-18 0 9 9 0 0118 0z', 'PAN No.', $emp['pan_no'] ?? '');
                infoRow('M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'Department', $emp['department'] ?? '');
                infoRow('M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'Manager', $emp['manager'] ?? '');
                infoRow('M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'Joining Date', !empty($emp['joining_date']) ? date('d M Y', strtotime($emp['joining_date'])) : (!empty($emp['created_at']) ? date('d M Y', strtotime($emp['created_at'])) : ''));
                infoRow('M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z', 'Address', $emp['address'] ?? '');
                ?>
            </div>

            <!-- Documents -->
            <?php if (!empty($emp['aadhar_img']) || !empty($emp['pan_img'])): ?>
            <div id="documents" class="border-t border-slate-100 pt-5">
                <p class="text-sm font-semibold text-slate-700 mb-3">Documents</p>
                <div class="flex gap-3">
                    <?php if (!empty($emp['aadhar_img'])): ?>
                    <a href="<?= htmlspecialchars($emp['aadhar_img']) ?>" target="_blank"
                       class="flex items-center gap-2 px-4 py-2 bg-amber-50 text-amber-700 rounded-lg text-sm hover:bg-amber-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Aadhar Card
                    </a>
                    <?php endif; ?>
                    <?php if (!empty($emp['pan_img'])): ?>
                    <a href="<?= htmlspecialchars($emp['pan_img']) ?>" target="_blank"
                       class="flex items-center gap-2 px-4 py-2 bg-emerald-50 text-emerald-700 rounded-lg text-sm hover:bg-emerald-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        PAN Card
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
const emp = <?= json_encode($emp) ?>;

// Profile photo preview + auto upload
function previewAndSubmit(input) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        const preview = document.getElementById('photoPreview');
        if (preview.tagName === 'IMG') {
            preview.src = e.target.result;
        } else {
            // Replace div with img
            const img = document.createElement('img');
            img.id = 'photoPreview';
            img.src = e.target.result;
            img.className = 'w-28 h-28 rounded-full object-cover border-4 border-blue-100';
            preview.replaceWith(img);
        }
    };
    reader.readAsDataURL(input.files[0]);
    document.getElementById('photoForm').submit();
}
function exportEmpPDF() {
    const { jsPDF } = window.jspdf;

    function buildPDF(imgData) {
        const doc = new jsPDF();
        const pageW = doc.internal.pageSize.getWidth();

        // ── Header bar ──────────────────────────────────────────────────────
        doc.setFillColor(79, 70, 229);
        doc.rect(0, 0, pageW, 38, 'F');

        // Profile image (circle crop via canvas)
        if (imgData) {
            doc.addImage(imgData, 'JPEG', 10, 5, 28, 28, '', 'FAST');
        }

        // Name + ID on header
        doc.setTextColor(255, 255, 255);
        doc.setFontSize(16);
        doc.setFont('helvetica', 'bold');
        doc.text(emp.name, imgData ? 44 : 14, 18);
        doc.setFontSize(9);
        doc.setFont('helvetica', 'normal');
        doc.text('Employee ID: ' + emp.id, imgData ? 44 : 14, 26);
        doc.text('Generated: ' + new Date().toLocaleDateString('en-IN'), imgData ? 44 : 14, 33);

        // ── Section: Basic Info ──────────────────────────────────────────────
        let y = 50;
        doc.setTextColor(79, 70, 229);
        doc.setFontSize(11);
        doc.setFont('helvetica', 'bold');
        doc.text('EMPLOYEE DETAILS', 14, y);
        doc.setDrawColor(79, 70, 229);
        doc.setLineWidth(0.5);
        doc.line(14, y + 2, pageW - 14, y + 2);
        y += 10;

        const col1 = 14, col2 = 110;
        doc.setTextColor(100, 116, 139);
        doc.setFontSize(8);
        doc.setFont('helvetica', 'normal');

        function row(label, value, x, yPos) {
            doc.setTextColor(100, 116, 139);
            doc.setFont('helvetica', 'normal');
            doc.text(label, x, yPos);
            doc.setTextColor(30, 41, 59);
            doc.setFont('helvetica', 'bold');
            doc.text(value || 'N/A', x, yPos + 5);
        }

        row('Full Name',    emp.name,                    col1, y);
        row('Role',         emp.role,                    col2, y);
        y += 14;
        row('Work Type',    emp.work_type,               col1, y);
        row('Department',   emp.department || 'N/A',     col2, y);
        y += 14;
        row('Manager',      emp.manager || 'N/A',        col1, y);
        row('Joining Date', emp.joining_date || emp.created_at || 'N/A', col2, y);
        y += 14;
        row('Email',        emp.email || 'N/A',          col1, y);
        row('Contact',      emp.contact || 'N/A',        col2, y);
        y += 14;
        row('Address',      emp.address || 'N/A',        col1, y);
        y += 14;

        // ── Section: Salary ──────────────────────────────────────────────────
        y += 4;
        doc.setTextColor(79, 70, 229);
        doc.setFontSize(11);
        doc.setFont('helvetica', 'bold');
        doc.text('SALARY INFORMATION', 14, y);
        doc.line(14, y + 2, pageW - 14, y + 2);
        y += 10;

        row('Monthly Salary', 'Rs. ' + parseFloat(emp.salary).toLocaleString('en-IN', {minimumFractionDigits: 2}), col1, y);

        // Salary status badge
        const paid = emp.salary_status === 'Paid';
        doc.setFillColor(paid ? 220 : 254, paid ? 252 : 226, paid ? 231 : 226);
        doc.roundedRect(col2, y - 5, 40, 12, 3, 3, 'F');
        doc.setTextColor(paid ? 22 : 220, paid ? 163 : 38, paid ? 74 : 38);
        doc.setFontSize(9);
        doc.setFont('helvetica', 'bold');
        doc.text(emp.salary_status, col2 + 5, y + 3);
        y += 14;

        row('Aadhar No.', emp.aadhar_no || 'N/A', col1, y);
        row('PAN No.',    emp.pan_no    || 'N/A', col2, y);
        y += 20;

        // ── Footer ───────────────────────────────────────────────────────────
        doc.setFillColor(248, 250, 252);
        doc.rect(0, doc.internal.pageSize.getHeight() - 16, pageW, 16, 'F');
        doc.setTextColor(148, 163, 184);
        doc.setFontSize(8);
        doc.setFont('helvetica', 'normal');
        doc.text('This is a system generated document.', 14, doc.internal.pageSize.getHeight() - 6);
        doc.text('RST Admin Panel', pageW - 14, doc.internal.pageSize.getHeight() - 6, { align: 'right' });

        doc.save('employee_' + emp.id + '.pdf');
    }

    // Load profile image via canvas to handle CORS
    if (emp.profile_img) {
        const img = new Image();
        img.crossOrigin = 'anonymous';
        img.onload = function() {
            const canvas = document.createElement('canvas');
            canvas.width = img.width;
            canvas.height = img.height;
            const ctx = canvas.getContext('2d');
            // Circle crop
            ctx.beginPath();
            ctx.arc(img.width / 2, img.height / 2, Math.min(img.width, img.height) / 2, 0, Math.PI * 2);
            ctx.clip();
            ctx.drawImage(img, 0, 0);
            buildPDF(canvas.toDataURL('image/jpeg'));
        };
        img.onerror = () => buildPDF(null); // fallback without image
        img.src = emp.profile_img;
    } else {
        buildPDF(null);
    }
}
</script>
<?php include '../includes/layout_end.php'; ?>
