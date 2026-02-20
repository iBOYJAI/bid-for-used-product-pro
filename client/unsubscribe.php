<?php

/**
 * Unsubscribe from Product Updates
 */

require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/session.php';

require_login('client');

$client_id = get_user_id();

// Deactivate subscription
execute_query("UPDATE subscriptions SET status = 'inactive' WHERE client_id = ?", [$client_id]);

header('Location: ../client/dashboard.php?success=unsubscribed');
exit;
