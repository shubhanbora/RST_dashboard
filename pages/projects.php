<?php
require_once '../config.php';
requireLogin();
$pageTitle = 'Projects';

$projects  = readData('projects');
$types     = ['E-commerce','App/Software','Custom Website','Portfolio'];
$statuses  = ['Ongoing','Completed'];

$filterType   = $_GET['type'] ?? '';
$filterStatus = $_GET['status'] ?? '';
$search       = strtolower(trim($_GET['search'] ?? ''));

$filtered = array_filter($projects, function($p) use ($filterType, $filterStatus, $search) {
    if ($filterType   && $p['type']   !== $filterType)   return false;
    if ($filterStatus && $p['status'] !== $filterStatus) return false;
    if ($search && strpos(strtolower($p['name']), $search) === false) return false;
    return true;
});

include '../includes/layout.php';
?>
<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <form method="GET" class="flex flex-wrap gap-3 items-center">
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search project..."
            class="px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 w-48">
        <select name="type" class="px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            <option value="">All Types</option>
            <?php foreach ($types as $t): ?>
            <option value="<?= $t ?>" <?= $filterType === $t ? 'selected' : '' ?>><?= $t ?></option>
            <?php endforeach; ?>
        </select>
        <select name="status" class="px-3 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            <option value="">All Statuses</option>
            <?php foreach ($statuses as $s): ?>
            <option value="<?= $s ?>" <?= $filterStatus === $s ? 'selected' : '' ?>><?= $s ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">Filter</button>
        <a href="projects.php" class="px-4 py-2 bg-slate-200 text-slate-700 rounded-lg text-sm hover:bg-slate-300">Reset</a>
    </form>
    <a href="project_add.php" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Project
    </a>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
    <?php foreach ($filtered as $proj): ?>
    <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-shadow">
        <?php if (!empty($proj['homepage_image'])): ?>
        <img src="<?= htmlspecialchars($proj['homepage_image']) ?>" alt="Project" class="w-full h-36 object-cover">
        <?php else: ?>
        <div class="w-full h-36 bg-gradient-to-br from-blue-100 to-purple-100 flex items-center justify-center">
            <svg class="w-10 h-10 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        </div>
        <?php endif; ?>
        <div class="p-4">
            <div class="flex items-start justify-between gap-2 mb-2">
                <h3 class="font-semibold text-slate-800 text-sm"><?= htmlspecialchars($proj['name']) ?></h3>
                <span class="text-xs px-2 py-0.5 rounded-full flex-shrink-0 <?= ($proj['status'] ?? '') === 'Completed' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' ?>">
                    <?= htmlspecialchars($proj['status'] ?? '') ?>
                </span>
            </div>
            <p class="text-xs text-slate-400 mb-3"><?= htmlspecialchars($proj['type'] ?? '') ?></p>
            <div class="flex gap-2">
                <a href="project_detail.php?id=<?= $proj['id'] ?>" class="flex-1 text-center text-xs py-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 font-medium">View</a>
                <a href="../actions/project_action.php?action=delete&id=<?= $proj['id'] ?>"
                   onclick="return confirm('Delete this project?')"
                   class="flex-1 text-center text-xs py-1.5 bg-red-50 text-red-500 rounded-lg hover:bg-red-100 font-medium">Delete</a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php if (empty($filtered)): ?>
    <div class="col-span-3 bg-white rounded-xl shadow-sm p-10 text-center text-slate-400">No projects found.</div>
    <?php endif; ?>
</div>
<?php include '../includes/layout_end.php'; ?>
