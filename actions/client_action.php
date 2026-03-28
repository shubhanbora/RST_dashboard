<?php
require_once '../config.php';
requireLogin();

$action  = $_POST['action'] ?? $_GET['action'] ?? '';
$clients = readData('clients');

function handlePdfUpload(string $field): string {
    if (!isset($_FILES[$field]) || $_FILES[$field]['error'] !== UPLOAD_ERR_OK) return '';
    $ext = strtolower(pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION));
    if ($ext !== 'pdf') return '';
    $remoteName = 'uploads/agreement_' . uniqid() . '.pdf';
    return uploadToStorage($_FILES[$field]['tmp_name'], $remoteName);
}

if ($action === 'add') {
    $client = [
        'id'            => generateId('CLT'),
        'name'          => trim($_POST['name']),
        'contact'       => trim($_POST['contact']),
        'gst'           => trim($_POST['gst'] ?? ''),
        'project_name'  => trim($_POST['project_name'] ?? ''),
        'total_amount'  => floatval($_POST['total_amount'] ?? 0),
        'paid_amount'   => floatval($_POST['paid_amount'] ?? 0),
        'agreement_pdf' => handlePdfUpload('agreement_pdf'),
        'created_at'    => date('Y-m-d'),
    ];
    writeDocument('clients', $client);
    redirect('../pages/client_detail.php?id=' . $client['id'] . '&saved=1');
}

if ($action === 'edit') {
    $id = $_POST['id'];
    foreach ($clients as $client) {
        if ($client['id'] === $id) {
            $client['name']         = trim($_POST['name']);
            $client['contact']      = trim($_POST['contact']);
            $client['gst']          = trim($_POST['gst'] ?? '');
            $client['project_name'] = trim($_POST['project_name'] ?? '');
            $client['total_amount'] = floatval($_POST['total_amount'] ?? 0);
            $client['paid_amount']  = floatval($_POST['paid_amount'] ?? 0);
            $pdf = handlePdfUpload('agreement_pdf');
            if ($pdf) $client['agreement_pdf'] = $pdf;
            writeDocument('clients', $client);
            redirect('../pages/client_detail.php?id=' . $id . '&saved=1');
        }
    }
}

if ($action === 'delete') {
    $id = $_GET['id'] ?? $_POST['id'];
    deleteDocument('clients', $id);
    redirect('../pages/clients.php');
}

redirect('../pages/clients.php');
