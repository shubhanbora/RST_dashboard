<?php
require_once '../config.php';
requireLogin();

$id      = $_GET['id'] ?? '';
$clients = readData('clients');
$client  = null;
foreach ($clients as $c) { if ($c['id'] === $id) { $client = $c; break; } }
if (!$client) redirect('clients.php');

$pageTitle = 'Client: ' . htmlspecialchars($client['name']);
$mode      = $_GET['mode'] ?? 'view';
$saved     = isset($_GET['saved']);
$remaining = ($client['total_amount'] ?? 0) - ($client['paid_amount'] ?? 0);

include '../includes/layout.php';
?>

<?php if ($saved): ?>
<div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">Changes saved successfully.</div>
<?php endif; ?>

<!-- Breadcrumb -->
<nav class="text-sm text-slate-400 mb-5">
    <a href="dashboard.php" class="hover:text-blue-600">Home</a> &rsaquo;
    <a href="clients.php" class="hover:text-blue-600">Clients</a> &rsaquo;
    <span class="text-slate-600"><?= htmlspecialchars($client['name']) ?></span>
</nav>

<h1 class="text-2xl font-bold text-slate-800 mb-6">Client Profile: <?= htmlspecialchars($client['name']) ?></h1>

<div class="flex gap-6 items-start">

    <!-- Left Card -->
    <div class="w-56 flex-shrink-0">
        <div class="bg-white rounded-xl shadow-sm p-5 text-center">
            <div class="w-20 h-20 rounded-full bg-amber-100 flex items-center justify-center mx-auto">
                <svg class="w-10 h-10 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <p class="mt-3 font-semibold text-slate-800"><?= htmlspecialchars($client['name']) ?></p>
            <p class="text-xs text-slate-400 mt-0.5"><?= htmlspecialchars($client['project_name'] ?? '') ?></p>
            <?php $paid = $remaining <= 0; ?>
            <span class="inline-block mt-2 px-2.5 py-0.5 rounded-full text-xs font-medium <?= $paid ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' ?>">
                <?= $paid ? 'Fully Paid' : 'Payment Pending' ?>
            </span>

            <div class="mt-5 text-left border-t border-slate-100 pt-4">
                <p class="text-xs font-semibold text-slate-600 mb-2">Quick links</p>
                <a href="?id=<?= $client['id'] ?>&mode=edit" class="flex items-center gap-2 text-sm text-slate-600 hover:text-blue-600 py-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Edit Client
                </a>
                <?php if (!empty($client['agreement_pdf'])): ?>
                <a href="<?= htmlspecialchars($client['agreement_pdf']) ?>" target="_blank" class="flex items-center gap-2 text-sm text-slate-600 hover:text-blue-600 py-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Agreement PDF
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Right Panel -->
    <div class="flex-1">
        <?php if ($mode === 'edit'): ?>
        <!-- EDIT FORM -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="font-semibold text-slate-800">Edit Client</h2>
                <a href="?id=<?= $client['id'] ?>" class="text-sm text-slate-400 hover:text-slate-600">← Cancel</a>
            </div>
            <form method="POST" action="../actions/client_action.php" enctype="multipart/form-data" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" value="<?= $client['id'] ?>">
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Client Name</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($client['name']) ?>" required class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Contact</label>
                    <input type="text" name="contact" value="<?= htmlspecialchars($client['contact'] ?? '') ?>" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">GST Number</label>
                    <input type="text" name="gst" value="<?= htmlspecialchars($client['gst'] ?? '') ?>" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Project Name</label>
                    <input type="text" name="project_name" value="<?= htmlspecialchars($client['project_name'] ?? '') ?>" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Total Amount (Rs.)</label>
                    <input type="number" name="total_amount" value="<?= $client['total_amount'] ?? 0 ?>" min="0" step="0.01" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Paid Amount (Rs.)</label>
                    <input type="number" name="paid_amount" value="<?= $client['paid_amount'] ?? 0 ?>" min="0" step="0.01" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-medium text-slate-600 mb-1">Agreement PDF <span class="text-slate-400">(leave blank to keep existing)</span></label>
                    <input type="file" name="agreement_pdf" accept=".pdf" class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:bg-blue-50 file:text-blue-600">
                </div>
                <div class="sm:col-span-2 flex gap-3 pt-2">
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium">Save Changes</button>
                    <a href="?id=<?= $client['id'] ?>" class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-sm font-medium">Cancel</a>
                    <a href="../actions/client_action.php?action=delete&id=<?= $client['id'] ?>"
                       onclick="return confirm('Delete this client?')"
                       class="ml-auto px-6 py-2.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg text-sm font-medium">Delete Client</a>
                </div>
            </form>
        </div>

        <?php else: ?>
        <!-- VIEW MODE -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-5 pb-4 border-b border-slate-100">
                <div>
                    <p class="text-xs text-slate-400 mb-0.5">Client ID</p>
                    <p class="font-mono font-semibold text-slate-700"><?= htmlspecialchars($client['id']) ?></p>
                </div>
                <div class="flex gap-2">
                    <a href="?id=<?= $client['id'] ?>&mode=edit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium">Edit Client</a>
                    <button onclick="exportClientPDF()" class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-lg text-sm font-medium">Download PDF</button>
                    <a href="../actions/client_action.php?action=delete&id=<?= $client['id'] ?>"
                       onclick="return confirm('Delete this client?')"
                       class="px-4 py-2 border border-red-200 hover:bg-red-50 text-red-500 rounded-lg text-sm font-medium">Delete</a>
                </div>
            </div>

            <!-- Info Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-10 gap-y-5 mb-6">
                <?php
                function clientRow($icon, $label, $value, $badge = false, $badgeColor = '') {
                    if (!isset($value) || $value === '') return;
                    echo '<div class="flex items-start gap-3">';
                    echo '<div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">';
                    echo '<svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="' . $icon . '"/></svg>';
                    echo '</div><div><p class="text-xs text-slate-400">' . $label . '</p>';
                    if ($badge) echo '<span class="inline-block mt-0.5 px-2.5 py-0.5 rounded-full text-xs font-medium ' . $badgeColor . '">' . htmlspecialchars($value) . '</span>';
                    else echo '<p class="text-sm font-semibold text-slate-800">' . htmlspecialchars($value) . '</p>';
                    echo '</div></div>';
                }
                clientRow('M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'Client Name', $client['name']);
                clientRow('M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z', 'Contact', $client['contact'] ?? '');
                clientRow('M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'Project Name', $client['project_name'] ?? '');
                clientRow('M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z', 'GST Number', $client['gst'] ?? '');
                clientRow('M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'Added On', !empty($client['created_at']) ? date('d M Y', strtotime($client['created_at'])) : '');
                ?>
            </div>

            <!-- Payment Summary -->
            <div class="border-t border-slate-100 pt-5">
                <p class="text-sm font-semibold text-slate-700 mb-4">Payment Summary</p>
                <div class="grid grid-cols-3 gap-4">
                    <div class="bg-slate-50 rounded-xl p-4 text-center">
                        <p class="text-xs text-slate-400 mb-1">Total Amount</p>
                        <p class="text-lg font-bold text-slate-800">Rs. <?= number_format($client['total_amount'] ?? 0, 2) ?></p>
                    </div>
                    <div class="bg-green-50 rounded-xl p-4 text-center">
                        <p class="text-xs text-slate-400 mb-1">Paid</p>
                        <p class="text-lg font-bold text-green-600">Rs. <?= number_format($client['paid_amount'] ?? 0, 2) ?></p>
                    </div>
                    <div class="<?= $remaining > 0 ? 'bg-red-50' : 'bg-green-50' ?> rounded-xl p-4 text-center">
                        <p class="text-xs text-slate-400 mb-1">Remaining</p>
                        <p class="text-lg font-bold <?= $remaining > 0 ? 'text-red-500' : 'text-green-600' ?>">Rs. <?= number_format(max(0, $remaining), 2) ?></p>
                    </div>
                </div>
                <!-- Progress bar -->
                <?php $pct = ($client['total_amount'] ?? 0) > 0 ? min(100, round(($client['paid_amount'] / $client['total_amount']) * 100)) : 0; ?>
                <div class="mt-4">
                    <div class="flex justify-between text-xs text-slate-400 mb-1">
                        <span>Payment Progress</span><span><?= $pct ?>%</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2">
                        <div class="<?= $pct >= 100 ? 'bg-green-500' : 'bg-blue-500' ?> h-2 rounded-full transition-all" style="width:<?= $pct ?>%"></div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
const client = <?= json_encode($client) ?>;
function exportClientPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    const pageW = doc.internal.pageSize.getWidth();

    doc.setFillColor(245, 158, 11);
    doc.rect(0, 0, pageW, 38, 'F');
    doc.setTextColor(255, 255, 255);
    doc.setFontSize(16); doc.setFont('helvetica', 'bold');
    doc.text(client.name, 14, 18);
    doc.setFontSize(9); doc.setFont('helvetica', 'normal');
    doc.text('Client ID: ' + client.id, 14, 26);
    doc.text('Generated: ' + new Date().toLocaleDateString('en-IN'), 14, 33);

    let y = 50;
    doc.setTextColor(245, 158, 11); doc.setFontSize(11); doc.setFont('helvetica', 'bold');
    doc.text('CLIENT DETAILS', 14, y);
    doc.setDrawColor(245, 158, 11); doc.setLineWidth(0.5);
    doc.line(14, y + 2, pageW - 14, y + 2);
    y += 12;

    function row(label, value, x, yPos) {
        doc.setTextColor(100, 116, 139); doc.setFont('helvetica', 'normal'); doc.setFontSize(8);
        doc.text(label, x, yPos);
        doc.setTextColor(30, 41, 59); doc.setFont('helvetica', 'bold');
        doc.text(String(value || 'N/A'), x, yPos + 5);
    }
    row('Client Name',   client.name,                14,  y); row('Contact', client.contact||'N/A', 110, y); y += 14;
    row('Project Name',  client.project_name||'N/A', 14,  y); row('GST No.', client.gst||'N/A',     110, y); y += 20;

    doc.setTextColor(245, 158, 11); doc.setFontSize(11); doc.setFont('helvetica', 'bold');
    doc.text('PAYMENT SUMMARY', 14, y);
    doc.line(14, y + 2, pageW - 14, y + 2);
    y += 12;

    const remaining = (parseFloat(client.total_amount)||0) - (parseFloat(client.paid_amount)||0);
    row('Total Amount', 'Rs. ' + parseFloat(client.total_amount||0).toFixed(2), 14,  y);
    row('Paid Amount',  'Rs. ' + parseFloat(client.paid_amount||0).toFixed(2),  110, y); y += 14;
    row('Remaining',    'Rs. ' + Math.max(0, remaining).toFixed(2),             14,  y);
    row('Status',       remaining <= 0 ? 'Fully Paid' : 'Payment Pending',      110, y); y += 20;

    doc.setFillColor(248, 250, 252);
    doc.rect(0, doc.internal.pageSize.getHeight() - 16, pageW, 16, 'F');
    doc.setTextColor(148, 163, 184); doc.setFontSize(8); doc.setFont('helvetica', 'normal');
    doc.text('This is a system generated document.', 14, doc.internal.pageSize.getHeight() - 6);
    doc.text('RST Admin Panel', pageW - 14, doc.internal.pageSize.getHeight() - 6, { align: 'right' });
    doc.save('client_' + client.id + '.pdf');
}
</script>
<?php include '../includes/layout_end.php'; ?>
