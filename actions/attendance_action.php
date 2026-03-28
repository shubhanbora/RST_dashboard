<?php
require_once '../config.php';

// Allow both admin session AND fingerprint device API calls
$isApi = !isset($_SESSION['admin_logged_in']);
if ($isApi) {
    // Fingerprint devices will POST with an API key for security
    $apiKey = $_POST['api_key'] ?? $_GET['api_key'] ?? '';
    if ($apiKey !== 'RST_FINGERPRINT_KEY') {
        http_response_code(403);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }
} else {
    requireLogin();
}

$empId  = trim($_POST['emp_id'] ?? '');
$date   = trim($_POST['date']   ?? date('Y-m-d'));
$status = trim($_POST['status'] ?? 'present'); // present / absent / leave
$docId  = trim($_POST['doc_id'] ?? '');

if (!$empId || !in_array($status, ['present', 'absent', 'leave'])) {
    if ($isApi) { echo json_encode(['error' => 'Invalid data']); exit; }
    redirect('../pages/attendance.php');
}

// Use existing doc ID or generate new one
if (!$docId) {
    $docId = 'ATT-' . $empId . '-' . $date;
}

$record = [
    'id'         => $docId,
    'emp_id'     => $empId,
    'date'       => $date,
    'status'     => $status,
    'leave_days' => $status === 'leave' ? 1 : 0,
    'marked_at'  => date('Y-m-d H:i:s'),
];

writeDocument('attendance', $record);

if ($isApi) {
    echo json_encode(['success' => true, 'record' => $record]);
} else {
    redirect('../pages/attendance.php?date=' . $date);
}
