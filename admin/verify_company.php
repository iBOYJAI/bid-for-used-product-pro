<?php
/**
 * Verify Company (Admin Only)
 */

require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/session.php';

require_login('admin');

$company_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Update company verification status
execute_query("UPDATE companies SET verified_status = 'verified' WHERE company_id = ?", [$company_id]);

header('Location: verify_companies.php?success=verified');
exit;
?>
