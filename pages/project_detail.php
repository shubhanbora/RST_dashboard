<?php
require_once '../config.php';
requireLogin();

$id       = $_GET['id'] ?? '';
$projects = readData('projects');
$proj     = null;
foreach ($projects as $p) { if ($p['id'] === $id) { $proj = $p; break; } }
if (!$proj) redirect('projects.php');

$pageTitle = 'Project: ' . htmlspecialchars($proj['name']);
$types     = ['E-commerce','App/Software','Custom Website','Portfolio'];
$mode      = $_GET['mode'] ?? 'view';
$saved     = isset($_GET['saved']);

include '../includes/layout.php';
?>

<?php if ($saved): ?>
<div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">Changes saved successfully.</div>
<?php endif; ?>

<!-- Breadcrumb -->
<nav class="text-sm text-slate-400 mb-5">
    <a href="dashboard.php" class="hover:text-blue-600">Home</a> &rsaquo;
    <a href="projects.php" class="hover:text-blue-600">Projects</a> &rsaquo;
    <span class="text-slate-600"><?= htmlspecialchars($proj['name']) ?></span>
</nav>

<h1 class="text-2xl font-bold text-slate-800 mb-6">Project: <?= htmlspecialchars($proj['name']) ?></h1>

<div class="flex gap-6 items-start">

    <!-- Left Card: Image + Quick Links -->
    <div class="w-56 flex-shrink-0">
        <div class="bg-white rounded-xl shadow-sm p-5 text-center">
            <?php if (!empty($proj['homepage_image'])): ?>
            <img src="<?= htmlspecialchars($proj['homepage_image']) ?>" class="w-full h-32 object-cover rounded-lg mb-3">
            <?php else: ?>
            <div class="w-full h-32 bg-blue-50 rounded-lg flex items-center justify-center mb-3">
                <svg class="w-12 h-12 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <?php endif; ?>
            <p class="font-semibold text-slate-800 text-sm"><?= htmlspecialchars($proj['name']) ?></p>
            <p class="text-xs text-slate-400 mt-0.5"><?= htmlspecialchars($proj['type'] ?? '') ?></p>
            <?php $completed = ($proj['status'] ?? '') === 'Completed'; ?>
            <span class="inline-block mt-2 px-2.5 py-0.5 rounded-full text-xs font-medium <?= $completed ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' ?>">
                <?= htmlspecialchars($proj['status'] ?? '') ?>
            </span>

            <div class="mt-5 text-left border-t border-slate-100 pt-4">
                <p class="text-xs font-semibold text-slate-600 mb-2">Quick links</p>
                <a href="?id=<?= $proj['id'] ?>&mode=edit" class="flex items-center gap-2 text-sm text-slate-600 hover:text-blue-600 py-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Edit Project
                </a>
                <?php if (!empty($proj['link'])): ?>
                <a href="<?= htmlspecialchars($proj['link']) ?>" target="_blank" class="flex items-center gap-2 text-sm text-slate-600 hover:text-blue-600 py-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    Visit Website
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
                <h2 class="font-semibold text-slate-800">Edit Project</h2>
                <a href="?id=<?= $proj['id'] ?>" class="text-sm text-slate-400 hover:text-slate-600">← Cancel</a>
            </div>
            <form method="POST" action="../actions/project_action.php" enctype="multipart/form-data" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" value="<?= htmlspecialchars($proj['id']) ?>">
                <input type="hidden" name="homepage_image_url" id="imageUrlHidden" value="">

                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Project Name</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($proj['name']) ?>" required class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Type</label>
                    <select name="type" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <?php foreach ($types as $t): ?>
                        <option value="<?= $t ?>" <?= ($proj['type'] ?? '') === $t ? 'selected' : '' ?>><?= $t ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Status</label>
                    <select name="status" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <option value="Ongoing"   <?= ($proj['status'] ?? '') === 'Ongoing'   ? 'selected' : '' ?>>Ongoing</option>
                        <option value="Completed" <?= ($proj['status'] ?? '') === 'Completed' ? 'selected' : '' ?>>Completed</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Project Link</label>
                    <input type="url" name="link" value="<?= htmlspecialchars($proj['link'] ?? '') ?>" placeholder="https://..." class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>

                <!-- Image upload: file OR URL -->
                <div class="sm:col-span-2">
                    <label class="block text-xs font-medium text-slate-600 mb-2">Homepage Image</label>
                    <div class="flex gap-2 mb-2">
                        <button type="button" onclick="switchTab('file')" id="tabFile"
                            class="px-3 py-1.5 text-xs rounded-lg bg-blue-600 text-white font-medium">Upload File</button>
                        <button type="button" onclick="switchTab('url')" id="tabUrl"
                            class="px-3 py-1.5 text-xs rounded-lg bg-slate-100 text-slate-600 font-medium">Paste URL</button>
                    </div>
                    <div id="panelFile">
                        <input type="file" name="homepage_image" accept="image/*" onchange="previewImg(this)"
                            class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:bg-blue-50 file:text-blue-600">
                    </div>
                    <div id="panelUrl" class="hidden">
                        <input type="url" id="imgUrlInput" placeholder="https://example.com/image.jpg"
                            class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                            oninput="previewUrl(this.value)">
                    </div>
                    <div id="imgPreviewBox" class="mt-3 <?= empty($proj['homepage_image']) ? 'hidden' : '' ?>">
                        <img id="imgPreview" src="<?= htmlspecialchars($proj['homepage_image'] ?? '') ?>" class="h-32 rounded-lg object-cover border border-slate-200">
                    </div>
                </div>

                <div class="sm:col-span-2 flex gap-3 pt-2">
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium">Save Changes</button>
                    <a href="?id=<?= $proj['id'] ?>" class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-sm font-medium">Cancel</a>
                    <a href="../actions/project_action.php?action=delete&id=<?= $proj['id'] ?>"
                       onclick="return confirm('Delete this project?')"
                       class="ml-auto px-6 py-2.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg text-sm font-medium">Delete Project</a>
                </div>
            </form>
        </div>

        <?php else: ?>
        <!-- VIEW MODE -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-5 pb-4 border-b border-slate-100">
                <div>
                    <p class="text-xs text-slate-400 mb-0.5">Project ID</p>
                    <p class="font-mono font-semibold text-slate-700"><?= htmlspecialchars($proj['id']) ?></p>
                </div>
                <div class="flex gap-2">
                    <a href="?id=<?= $proj['id'] ?>&mode=edit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium">Edit Project</a>
                    <button onclick="exportProjPDF()" class="px-4 py-2 border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-lg text-sm font-medium">Download PDF</button>
                    <a href="../actions/project_action.php?action=delete&id=<?= $proj['id'] ?>"
                       onclick="return confirm('Delete this project?')"
                       class="px-4 py-2 border border-red-200 hover:bg-red-50 text-red-500 rounded-lg text-sm font-medium">Delete</a>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-10 gap-y-5">
                <?php
                function projRow($icon, $label, $value, $badge = false, $badgeColor = '') {
                    if (empty($value)) return;
                    echo '<div class="flex items-start gap-3">';
                    echo '<div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">';
                    echo '<svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="' . $icon . '"/></svg>';
                    echo '</div><div><p class="text-xs text-slate-400">' . $label . '</p>';
                    if ($badge) {
                        echo '<span class="inline-block mt-0.5 px-2.5 py-0.5 rounded-full text-xs font-medium ' . $badgeColor . '">' . htmlspecialchars($value) . '</span>';
                    } else {
                        echo '<p class="text-sm font-semibold text-slate-800">' . htmlspecialchars($value) . '</p>';
                    }
                    echo '</div></div>';
                }
                projRow('M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'Project Name', $proj['name']);
                projRow('M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z', 'Type', $proj['type'] ?? '');
                $sc = ($proj['status'] ?? '') === 'Completed' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700';
                projRow('M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'Status', $proj['status'] ?? '', true, $sc);
                projRow('M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'Created', !empty($proj['created_at']) ? date('d M Y', strtotime($proj['created_at'])) : '');
                ?>
                <?php if (!empty($proj['link'])): ?>
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400">Project Link</p>
                        <a href="<?= htmlspecialchars($proj['link']) ?>" target="_blank" class="text-sm font-semibold text-blue-600 hover:underline"><?= htmlspecialchars($proj['link']) ?></a>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
const proj = <?= json_encode($proj) ?>;

function switchTab(tab) {
    document.getElementById('panelFile').classList.toggle('hidden', tab !== 'file');
    document.getElementById('panelUrl').classList.toggle('hidden', tab !== 'url');
    document.getElementById('tabFile').className = 'px-3 py-1.5 text-xs rounded-lg font-medium ' + (tab === 'file' ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-600');
    document.getElementById('tabUrl').className  = 'px-3 py-1.5 text-xs rounded-lg font-medium ' + (tab === 'url'  ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-600');
}

function previewImg(input) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('imgPreview').src = e.target.result;
        document.getElementById('imgPreviewBox').classList.remove('hidden');
    };
    reader.readAsDataURL(input.files[0]);
}

function previewUrl(url) {
    if (!url) return;
    document.getElementById('imgPreview').src = url;
    document.getElementById('imgPreviewBox').classList.remove('hidden');
    document.getElementById('imageUrlHidden').value = url;
}

function exportProjPDF() {
    const { jsPDF } = window.jspdf;

    function buildPDF(imgData) {
        const doc  = new jsPDF();
        const pageW = doc.internal.pageSize.getWidth();

        // Header
        doc.setFillColor(79, 70, 229);
        doc.rect(0, 0, pageW, 38, 'F');
        if (imgData) doc.addImage(imgData, 'JPEG', 10, 5, 48, 28, '', 'FAST');
        doc.setTextColor(255, 255, 255);
        doc.setFontSize(16); doc.setFont('helvetica', 'bold');
        doc.text(proj.name, imgData ? 64 : 14, 18);
        doc.setFontSize(9); doc.setFont('helvetica', 'normal');
        doc.text('Project ID: ' + proj.id, imgData ? 64 : 14, 26);
        doc.text('Generated: ' + new Date().toLocaleDateString('en-IN'), imgData ? 64 : 14, 33);

        let y = 50;
        doc.setTextColor(79, 70, 229); doc.setFontSize(11); doc.setFont('helvetica', 'bold');
        doc.text('PROJECT DETAILS', 14, y);
        doc.setDrawColor(79, 70, 229); doc.setLineWidth(0.5);
        doc.line(14, y + 2, pageW - 14, y + 2);
        y += 12;

        function row(label, value, x, yPos) {
            doc.setTextColor(100, 116, 139); doc.setFont('helvetica', 'normal'); doc.setFontSize(8);
            doc.text(label, x, yPos);
            doc.setTextColor(30, 41, 59); doc.setFont('helvetica', 'bold');
            doc.text(value || 'N/A', x, yPos + 5);
        }
        row('Project Name', proj.name,              14,  y); row('Type',    proj.type || 'N/A',   110, y); y += 14;
        row('Status',       proj.status || 'N/A',   14,  y); row('Created', proj.created_at||'N/A',110, y); y += 14;
        row('Link',         proj.link || 'N/A',     14,  y); y += 20;

        // Footer
        doc.setFillColor(248, 250, 252);
        doc.rect(0, doc.internal.pageSize.getHeight() - 16, pageW, 16, 'F');
        doc.setTextColor(148, 163, 184); doc.setFontSize(8); doc.setFont('helvetica', 'normal');
        doc.text('This is a system generated document.', 14, doc.internal.pageSize.getHeight() - 6);
        doc.text('RST Admin Panel', pageW - 14, doc.internal.pageSize.getHeight() - 6, { align: 'right' });
        doc.save('project_' + proj.id + '.pdf');
    }

    if (proj.homepage_image) {
        const img = new Image(); img.crossOrigin = 'anonymous';
        img.onload = function() {
            const canvas = document.createElement('canvas');
            canvas.width = img.width; canvas.height = img.height;
            canvas.getContext('2d').drawImage(img, 0, 0);
            buildPDF(canvas.toDataURL('image/jpeg'));
        };
        img.onerror = () => buildPDF(null);
        img.src = proj.homepage_image;
    } else { buildPDF(null); }
}
</script>
<?php include '../includes/layout_end.php'; ?>
