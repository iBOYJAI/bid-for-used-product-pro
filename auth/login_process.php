<?php
/**
 * Login Process Handler
 */

require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/validation.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/login.php');
    exit;
}

// Sanitize inputs
$email = sanitize_input($_POST['email']);
$password = $_POST['password']; // Don't sanitize password

// Validate inputs
$errors = [];

if (empty($email) || !validate_email($email)) {
    $errors[] = 'Valid email is required';
}

if (empty($password)) {
    $errors[] = 'Password is required';
}

if (!empty($errors)) {
    header('Location: ../pages/login.php?error=invalid');
    exit;
}

// Check user credentials
$sql = "SELECT u.*, c.company_id 
        FROM users u 
        LEFT JOIN companies c ON u.user_id = c.user_id 
        WHERE u.email = ?";
$user = fetch_one($sql, [$email]);

if (!$user || !verify_password($password, $user['password'])) {
    header('Location: ../pages/login.php?error=invalid');
    exit;
}

// Check if user is active
if ($user['status'] !== 'active') {
    header('Location: ../pages/login.php?error=inactive');
    exit;
}

// Set session and redirect
set_user_session($user);
redirect_to_dashboard();
?>
