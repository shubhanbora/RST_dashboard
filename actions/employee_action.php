<?php
require_once '../config.php';
requireLogin();

$action    = $_POST['action'] ?? $_GET['action'] ?? '';
$employees = readData('employees');

/**
 * Upload employee file to Firebase Storage, return public URL or null
 */
function uploadEmpFile(string $field): ?string {
    if (!isset($_FILES[$field]) || $_FILES[$field]['error'] !== UPLOAD_ERR_OK) return null;
    $ext     = strtolower(pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf'];
    if (!in_array($ext, $allowed)) return null;
    $remoteName = 'uploads/' . $field . '_' . uniqid() . '.' . $ext;
    return uploadToStorage($_FILES[$field]['tmp_name'], $remoteName) ?: null;
}

if ($action === 'add') {
    $emp = [
        'id'            => generateId('EMP'),
        'name'          => trim($_POST['name']),
        'email'         => trim($_POST['email'] ?? ''),
        'contact'       => trim($_POST['contact'] ?? ''),
        'role'          => $_POST['role'],
        'work_type'     => $_POST['work_type'],
        'department'    => trim($_POST['department'] ?? ''),
        'manager'       => trim($_POST['manager'] ?? ''),
        'salary'        => floatval($_POST['salary']),
        'salary_status' => $_POST['salary_status'],
        'aadhar_no'     => trim($_POST['aadhar_no'] ?? ''),
        'pan_no'        => trim($_POST['pan_no'] ?? ''),
        'address'       => trim($_POST['address'] ?? ''),
        'joining_date'  => trim($_POST['joining_date'] ?? date('Y-m-d')),
        'profile_img'   => uploadEmpFile('profile_img') ?? '',
        'aadhar_img'    => uploadEmpFile('aadhar_img')  ?? '',
        'pan_img'       => uploadEmpFile('pan_img')     ?? '',
        'created_at'    => date('Y-m-d'),
    ];
    writeDocument('employees', $emp);
    redirect('../pages/employee_detail.php?id=' . $emp['id']);
}

if ($action === 'edit') {
    $id = $_POST['id'];
    foreach ($employees as $emp) {
        if ($emp['id'] === $id) {
            $emp['name']          = trim($_POST['name']);
            $emp['email']         = trim($_POST['email'] ?? '');
            $emp['contact']       = trim($_POST['contact'] ?? '');
            $emp['role']          = $_POST['role'];
            $emp['work_type']     = $_POST['work_type'];
            $emp['department']    = trim($_POST['department'] ?? '');
            $emp['manager']       = trim($_POST['manager'] ?? '');
            $emp['salary']        = floatval($_POST['salary']);
            $emp['salary_status'] = $_POST['salary_status'];
            $emp['aadhar_no']     = trim($_POST['aadhar_no'] ?? '');
            $emp['pan_no']        = trim($_POST['pan_no'] ?? '');
            $emp['address']       = trim($_POST['address'] ?? '');
            $emp['joining_date']  = trim($_POST['joining_date'] ?? '');
            $p = uploadEmpFile('profile_img'); if ($p) $emp['profile_img'] = $p;
            $a = uploadEmpFile('aadhar_img');  if ($a) $emp['aadhar_img']  = $a;
            $n = uploadEmpFile('pan_img');     if ($n) $emp['pan_img']     = $n;
            writeDocument('employees', $emp);
            break;
        }
    }
    redirect('../pages/employee_detail.php?id=' . $id . '&saved=1');
}

if ($action === 'delete') {
    $id = $_GET['id'] ?? $_POST['id'];
    deleteDocument('employees', $id);
    redirect('../pages/employees.php');
}

redirect('../pages/employees.php');
