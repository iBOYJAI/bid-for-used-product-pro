<?php
/**
 * Toggle User Status (Admin Only)
 */

require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/session.php';

require_login('admin');

$user_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$current_user_id = get_user_id();

//Prevent self-modification
if ($user_id == $current_user_id) {
    header('Location: manage_users.php?error=self_modify');
    exit;
}

// Get user
$user = fetch_one("SELECT * FROM users WHERE user_id = ?", [$user_id]);

if (!$user) {
    header('Location: manage_users.php');
    exit;
}

// Toggle status
$new_status = ($user['status'] === 'active') ? 'inactive' : 'active';
execute_query("UPDATE users SET status = ? WHERE user_id = ?", [$new_status, $user_id]);

header('Location: manage_users.php?success=status_updated');
exit;
?>
