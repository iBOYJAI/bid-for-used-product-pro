<?php
/**
 * Client Registration Process Handler
 */

require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/validation.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/register_client.php');
    exit;
}

// Sanitize inputs
$name = sanitize_input($_POST['name']);
$email = sanitize_input($_POST['email']);
$contact = sanitize_input($_POST['contact']);
$address = sanitize_input($_POST['address']);
$dealership_details = sanitize_input($_POST['dealership_details'] ?? '');
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// Validate inputs
$errors = [];

if (empty($name) || empty($email) || empty($contact) || empty($address) || empty($password)) {
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
    header('Location: ../pages/register_client.php?error=email_exists');
    exit;
}

if (!empty($errors)) {
    header('Location: ../pages/register_client.php?error=validation');
    exit;
}

// Insert into users table
try {
    $hashed_password = hash_password($password);
    
    // Include dealership details in address if provided
    $full_address = $address;
    if (!empty($dealership_details)) {
        $full_address .= "\n\nDealership Details: " . $dealership_details;
    }
    
    $sql = "INSERT INTO users (role, name, email, password, contact, address, status) 
            VALUES ('client', ?, ?, ?, ?, ?, 'active')";
    execute_query($sql, [$name, $email, $hashed_password, $contact, $full_address]);
    
    // Redirect to login
    header('Location: ../pages/login.php?success=registered');
    exit;
    
} catch (Exception $e) {
    header('Location: ../pages/register_client.php?error=database');
    exit;
}
?>
