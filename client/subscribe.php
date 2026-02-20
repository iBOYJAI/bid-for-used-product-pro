<?php

/**
 * Subscribe to Product Updates
 */

require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/session.php';

require_login('client');

$client_id = get_user_id();

// Check if already subscribed
$existing = fetch_one("SELECT * FROM subscriptions WHERE client_id = ?", [$client_id]);

if ($existing) {
    // Reactivate subscription
    execute_query("UPDATE subscriptions SET status = 'active' WHERE client_id = ?", [$client_id]);
} else {
    // Create new subscription
    execute_query("INSERT INTO subscriptions (client_id, status) VALUES (?, 'active')", [$client_id]);
}

header('Location: ../client/dashboard.php?success=subscribed');
exit;
