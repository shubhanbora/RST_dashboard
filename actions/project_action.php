<?php
require_once '../config.php';
requireLogin();

$action   = $_POST['action'] ?? $_GET['action'] ?? '';
$projects = readData('projects');

function handleImageUpload(string $field): string {
    // Check for pasted URL first
    $urlFromInput = trim($_POST['homepage_image_url'] ?? '');
    if ($urlFromInput && filter_var($urlFromInput, FILTER_VALIDATE_URL)) {
        return $urlFromInput;
    }
    if (!isset($_FILES[$field]) || $_FILES[$field]['error'] !== UPLOAD_ERR_OK) return '';
    $ext     = strtolower(pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    if (!in_array($ext, $allowed)) return '';
    $remoteName = 'uploads/proj_' . uniqid() . '.' . $ext;
    return uploadToStorage($_FILES[$field]['tmp_name'], $remoteName);
}

if ($action === 'add') {
    $proj = [
        'id'             => generateId('PRJ'),
        'name'           => trim($_POST['name']),
        'type'           => $_POST['type'],
        'status'         => $_POST['status'],
        'link'           => trim($_POST['link'] ?? ''),
        'homepage_image' => handleImageUpload('homepage_image'),
        'created_at'     => date('Y-m-d'),
    ];
    writeDocument('projects', $proj);
    redirect('../pages/project_detail.php?id=' . $proj['id']);
}

if ($action === 'edit') {
    $id = $_POST['id'];
    foreach ($projects as $proj) {
        if ($proj['id'] === $id) {
            $proj['name']   = trim($_POST['name']);
            $proj['type']   = $_POST['type'];
            $proj['status'] = $_POST['status'];
            $proj['link']   = trim($_POST['link'] ?? '');
            $img = handleImageUpload('homepage_image');
            if ($img) $proj['homepage_image'] = $img;
            writeDocument('projects', $proj);
            break;
        }
    }
    redirect('../pages/project_detail.php?id=' . $id . '&saved=1');
}

if ($action === 'delete') {
    $id = $_GET['id'] ?? $_POST['id'];
    deleteDocument('projects', $id);
    redirect('../pages/projects.php');
}

redirect('../pages/projects.php');
