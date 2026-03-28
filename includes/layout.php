<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $pageTitle ?? 'Admin Dashboard' ?></title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>
<style>
  .sidebar-link.active { background: #2563eb; }
</style>
</head>
<body class="bg-slate-100 min-h-screen flex">
<?php include __DIR__ . '/sidebar.php'; ?>
<div class="flex-1 flex flex-col min-h-screen ml-64">
    <?php include __DIR__ . '/header.php'; ?>
    <main class="flex-1 p-6">
