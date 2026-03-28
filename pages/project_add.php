<?php
require_once '../config.php';
requireLogin();
$pageTitle = 'Add Project';
$types     = ['E-commerce','App/Software','Custom Website','Portfolio'];
include '../includes/layout.php';
?>
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="text-lg font-semibold text-slate-800 mb-6">New Project</h2>
        <form method="POST" action="../actions/project_action.php" enctype="multipart/form-data" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <input type="hidden" name="action" value="add">
            <input type="hidden" name="homepage_image_url" id="imageUrlHidden" value="">

            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">Project Name *</label>
                <input type="text" name="name" required class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">Type</label>
                <select name="type" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <?php foreach ($types as $t): ?><option value="<?= $t ?>"><?= $t ?></option><?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <option value="Ongoing">Ongoing</option>
                    <option value="Completed">Completed</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-600 mb-1">Project Link</label>
                <input type="url" name="link" placeholder="https://..." class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <!-- Image: file or URL -->
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
                <div id="imgPreviewBox" class="mt-3 hidden">
                    <img id="imgPreview" src="" class="h-32 rounded-lg object-cover border border-slate-200">
                </div>
            </div>

            <div class="sm:col-span-2 flex gap-3 pt-2">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium">Add Project</button>
                <a href="projects.php" class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-sm font-medium">Cancel</a>
            </div>
        </form>
    </div>
</div>
<script>
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
</script>
<?php include '../includes/layout_end.php'; ?>
