<?php
require_once '../config.php';
requireLogin();
$pageTitle = 'Add Employee';
$roles     = ['Frontend','Backend','UI/UX','Tester','Manager','Marketing','Android Developer','iOS Developer'];
$workTypes = ['Full-time','Part-time','Intern'];
include '../includes/layout.php';
?>
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="text-lg font-semibold text-slate-800 mb-6">New Employee</h2>
        <form method="POST" action="../actions/employee_action.php" enctype="multipart/form-data" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <input type="hidden" name="action" value="add">

            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">Full Name *</label>
                <input type="text" name="name" required class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">Email</label>
                <input type="email" name="email" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">Contact</label>
                <input type="text" name="contact" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">Role *</label>
                <input type="text" name="role" required list="roleList" placeholder="e.g. Backend Developer"
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
                <label class="block text-xs font-medium text-slate-600 mb-1">Work Type *</label>
                <select name="work_type" required class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <?php foreach ($workTypes as $w): ?><option value="<?= $w ?>"><?= $w ?></option><?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">Department</label>
                <input type="text" name="department" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">Manager</label>
                <input type="text" name="manager" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">Salary (₹) *</label>
                <input type="number" name="salary" min="0" step="0.01" required class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">Salary Status</label>
                <select name="salary_status" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <option value="Unpaid">Unpaid</option>
                    <option value="Paid">Paid</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">Joining Date</label>
                <input type="date" name="joining_date" value="<?= date('Y-m-d') ?>" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">Aadhar No.</label>
                <input type="text" name="aadhar_no"
                    pattern="\d{12}" maxlength="12" inputmode="numeric"
                    placeholder="12 digit number"
                    oninput="this.value=this.value.replace(/\D/g,'')"
                    class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">PAN No.</label>
                <input type="text" name="pan_no"
                    pattern="[A-Z]{5}[0-9]{4}[A-Z]{1}" maxlength="10"
                    placeholder="e.g. ABCDE1234F"
                    oninput="this.value=this.value.toUpperCase().replace(/[^A-Z0-9]/g,'')"
                    class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div class="sm:col-span-2">
                <label class="block text-xs font-medium text-slate-600 mb-1">Address</label>
                <input type="text" name="address" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <!-- Documents -->
            <div class="sm:col-span-2 border-t border-slate-100 pt-4">
                <p class="text-xs font-semibold text-slate-600 mb-3">Documents <span class="text-slate-400 font-normal">(optional)</span></p>
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
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium">Add Employee</button>
                <a href="employees.php" class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-sm font-medium">Cancel</a>
            </div>
        </form>
    </div>
</div>
<?php include '../includes/layout_end.php'; ?>
