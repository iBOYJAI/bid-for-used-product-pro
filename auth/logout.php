<?php
/**
 * Logout Handler
 */

require_once __DIR__ . '/../includes/session.php';

logout();

header('Location: ../pages/login.php');
exit;
?>
