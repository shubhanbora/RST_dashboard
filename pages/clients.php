<?php
require_once '../config.php';
requireLogin();
$pageTitle = 'Clients';

$clients     = readData('clients');
$filterPaid  = $_GET['paid'] ?? '';
$search      = strtolower(trim($_GET['search'] ?? ''));
$showAdd     = isset($_GET['add']);
$editId      = $_GET['edit'] ?? '';
$editClient  = null;

if ($editId) {
    foreach ($clients as $c) { if ($c['id'] === $editId) { $editClient = $c; break; } }
}

$filtered = array_filter($clients, function($c) use ($filterPaid, $search) {
    $remaining = ($c['total_amount'] ?? 0) - ($c['paid_amount'] ?? 0);
    if ($filterPaid === 'paid'   && $remaining > 0)  return false;
    if ($filterPaid === 'unpaid' && $remaining <= 0) return false;
    if ($search) {
        $haystack = strtolower($c['name'] . ' ' . ($c['project_name'] ?? ''));
        if (strpos($haystack, $search) === false) return false;
    }
    return true;
});

include '../includes/layout.php';
?>
<?php if (isset($_GET['added'])): ?>
<div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">Client added successfully.</div>
<?php endif; ?>
<?php if (isset($_GET['saved'])): ?>
<div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">Client updated successfully.</div>
<?php endif; ?>

<!-- Filters -->
<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <form method="GET" class="flex flex-wrap gap-3 items-center">
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search client or project..."
            class="px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 w-52">
        <select name="paid" class="px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            <option value="">All Clients</option>
            <option value="paid"   <?= $filterPaid === 'paid'   ? 'selected' : '' ?>>Paid</option>
            <option value="unpaid" <?= $filterPaid === 'unpaid' ? 'selected' : '' ?>>Unpaid</option>
        </select>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">Filter</button>
        <a href="clients.php" class="px-4 py-2 bg-slate-200 text-slate-700 rounded-lg text-sm hover:bg-slate-300">Reset</a>
    </form>
    <a href="clients.php?add=1" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Client
    </a>
</div>

<!-- Add / Edit Form -->
<?php if ($showAdd || $editClient): ?>
<div class="bg-white rounded-xl shadow-sm p-6 mb-6">
    <h2 class="text-base font-semibold text-slate-800 mb-5"><?= $editClient ? 'Edit Client' : 'New Client' ?></h2>
    <form method="POST" action="../actions/client_action.php" enctype="multipart/form-data" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <input type="hidden" name="action" value="<?= $editClient ? 'edit' : 'add' ?>">
        <?php if ($editClient): ?><input type="hidden" name="id" value="<?= $editClient['id'] ?>"><?php endif; ?>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Client Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($editClient['name'] ?? '') ?>" required
                class="w-full px-4 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Contact</label>
            <input type="text" name="contact" value="<?= htmlspecialchars($editClient['contact'] ?? '') ?>" required
                class="w-full px-4 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">GST Number <span class="text-slate-400">(optional)</span></label>
            <input type="text" name="gst" value="<?= htmlspecialchars($editClient['gst'] ?? '') ?>"
                class="w-full px-4 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Project Name</label>
            <input type="text" name="project_name" value="<?= htmlspecialchars($editClient['project_name'] ?? '') ?>"
                class="w-full px-4 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Total Amount (₹)</label>
            <input type="number" name="total_amount" value="<?= $editClient['total_amount'] ?? 0 ?>" min="0" step="0.01"
                class="w-full px-4 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Paid Amount (₹)</label>
            <input type="number" name="paid_amount" value="<?= $editClient['paid_amount'] ?? 0 ?>" min="0" step="0.01"
                class="w-full px-4 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>
        <div class="sm:col-span-2">
            <label class="block text-sm font-medium text-slate-700 mb-1">Agreement PDF <?= $editClient ? '(leave blank to keep existing)' : '' ?></label>
            <input type="file" name="agreement_pdf" accept=".pdf" class="w-full text-sm text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100">
        </div>
        <div class="sm:col-span-2 flex gap-3">
            <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium">
                <?= $editClient ? 'Save Changes' : 'Add Client' ?>
            </button>
            <a href="clients.php" class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-sm font-medium">Cancel</a>
        </div>
    </form>
</div>
<?php endif; ?>

<!-- Clients Table -->
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 border-b border-slate-200">
            <tr>
                <th class="text-left px-5 py-3 text-slate-600 font-semibold">Name</th>
                <th class="text-left px-5 py-3 text-slate-600 font-semibold">Contact</th>
                <th class="text-left px-5 py-3 text-slate-600 font-semibold">Project</th>
                <th class="text-left px-5 py-3 text-slate-600 font-semibold">Total</th>
                <th class="text-left px-5 py-3 text-slate-600 font-semibold">Paid</th>
                <th class="text-left px-5 py-3 text-slate-600 font-semibold">Remaining</th>
                <th class="text-left px-5 py-3 text-slate-600 font-semibold">Agreement</th>
                <th class="text-left px-5 py-3 text-slate-600 font-semibold">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            <?php foreach ($filtered as $client):
                $remaining = ($client['total_amount'] ?? 0) - ($client['paid_amount'] ?? 0);
            ?>
            <tr class="hover:bg-slate-50 transition-colors">
                <td class="px-5 py-3 font-medium text-slate-800">
                    <?= htmlspecialchars($client['name']) ?>
                    <?php if (!empty($client['gst'])): ?>
                    <span class="block text-xs text-slate-400">GST: <?= htmlspecialchars($client['gst']) ?></span>
                    <?php endif; ?>
                </td>
                <td class="px-5 py-3 text-slate-600"><?= htmlspecialchars($client['contact']) ?></td>
                <td class="px-5 py-3 text-slate-600"><?= htmlspecialchars($client['project_name'] ?? '-') ?></td>
                <td class="px-5 py-3 text-slate-700">₹<?= number_format($client['total_amount'] ?? 0, 2) ?></td>
                <td class="px-5 py-3 text-green-600">₹<?= number_format($client['paid_amount'] ?? 0, 2) ?></td>
                <td class="px-5 py-3">
                    <span class="font-medium <?= $remaining > 0 ? 'text-red-500' : 'text-green-600' ?>">
                        ₹<?= number_format(max(0, $remaining), 2) ?>
                    </span>
                    <?php if ($remaining <= 0): ?>
                    <span class="ml-1 text-xs bg-green-100 text-green-700 px-1.5 py-0.5 rounded-full">Paid</span>
                    <?php endif; ?>
                </td>
                <td class="px-5 py-3">
                    <?php if (!empty($client['agreement_pdf'])): ?>
                    <a href="<?= htmlspecialchars($client['agreement_pdf']) ?>" target="_blank"
                       class="text-blue-600 hover:text-blue-800 text-xs font-medium flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        View PDF
                    </a>
                    <?php else: ?>
                    <span class="text-slate-400 text-xs">None</span>
                    <?php endif; ?>
                </td>
                <td class="px-5 py-3">
                    <div class="flex gap-2">
                        <a href="client_detail.php?id=<?= $client['id'] ?>" class="text-blue-600 hover:text-blue-800 text-xs font-medium">View</a>
                        <a href="clients.php?edit=<?= $client['id'] ?>" class="text-slate-500 hover:text-slate-700 text-xs font-medium">Edit</a>
                        <a href="../actions/client_action.php?action=delete&id=<?= $client['id'] ?>"
                           onclick="return confirm('Delete this client?')"
                           class="text-red-500 hover:text-red-700 text-xs font-medium">Delete</a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($filtered)): ?>
            <tr><td colspan="8" class="px-5 py-8 text-center text-slate-400">No clients found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php include '../includes/layout_end.php'; ?>
