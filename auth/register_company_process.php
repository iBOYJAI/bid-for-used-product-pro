<?php

/**
 * Company Registration Process Handler
 */

require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/validation.php';
require_once __DIR__ . '/../includes/file_upload_helper.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/register_company.php');
    exit;
}

// Sanitize inputs
$company_name = sanitize_input($_POST['company_name']);
$owner_name = sanitize_input($_POST['owner_name']);
$email = sanitize_input($_POST['email']);
$contact = sanitize_input($_POST['contact']);
$address = sanitize_input($_POST['address']);
$gst_number = sanitize_input($_POST['gst_number'] ?? '');
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// Validate inputs
$errors = [];

if (empty($company_name) || empty($owner_name) || empty($email) || empty($contact) || empty($address) || empty($password)) {
    $errors[] = 'All required fields must be filled';
}

if (!validate_email($email)) {
    $errors[] = 'Invalid email format';
}

if (!validate_phone($contact)) {
    $errors[] = 'Invalid phone number';
}

if ($password !== $confirm_password) {
    $errors[] = 'Passwords do not match';
}

list($valid_password, $password_error) = validate_password($password);
if (!$valid_password) {
    $errors[] = $password_error;
}

// Check if email already exists
$existing_user = fetch_one("SELECT user_id FROM users WHERE email = ?", [$email]);
if ($existing_user) {
    header('Location: ../pages/register_company.php?error=email_exists');
    exit;
}

if (!empty($errors)) {
    header('Location: ../pages/register_company.php?error=validation');
    exit;
}

// Handle multiple file upload
if (!isset($_FILES['identity_proof']) || empty($_FILES['identity_proof']['name'][0])) {
    header('Location: ../pages/register_company.php?error=file_upload');
    exit;
}

list($upload_success, $filenames_or_error) = upload_multiple_files($_FILES['identity_proof'], IDENTITY_PROOF_DIR, ALLOWED_DOCUMENT_TYPES, 5);

if (!$upload_success) {
    header('Location: ../pages/register_company.php?error=file_upload');
    exit;
}

// Store filenames as JSON
$identity_proof_json = json_encode($filenames_or_error);

// Start transaction
try {
    begin_transaction();

    // Insert into users table
    $hashed_password = hash_password($password);
    $sql_user = "INSERT INTO users (role, name, email, password, contact, address, status) 
                 VALUES ('company', ?, ?, ?, ?, ?, 'active')";
    execute_query($sql_user, [$company_name, $email, $hashed_password, $contact, $address]);
    $user_id = get_last_insert_id();

    // Insert into companies table
    $sql_company = "INSERT INTO companies (user_id, company_name, owner_name, gst_number, identity_proof, verified_status) 
                    VALUES (?, ?, ?, ?, ?, 'pending')";
    execute_query($sql_company, [$user_id, $company_name, $owner_name, $gst_number, $identity_proof_json]);

    commit_transaction();

    // Redirect to login
    header('Location: ../pages/login.php?success=registered');
    exit;
} catch (Exception $e) {
    rollback_transaction();

    // Delete uploaded files on error
    foreach ($filenames_or_error as $filename) {
        delete_file(IDENTITY_PROOF_DIR . $filename);
    }

    header('Location: ../pages/register_company.php?error=database');
    exit;
}
