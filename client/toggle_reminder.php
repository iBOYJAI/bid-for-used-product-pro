<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';

header('Content-Type: application/json');

if (!is_logged_in() || get_user_role() !== 'client') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$product_id = isset($input['product_id']) ? (int)$input['product_id'] : 0;

if (!$product_id) {
    echo json_encode(['success' => false, 'message' => 'Invalid product ID']);
    exit;
}

$user_id = get_user_id();

// Check if reminder exists
$exists = fetch_one("SELECT reminder_id FROM product_reminders WHERE user_id = ? AND product_id = ?", [$user_id, $product_id]);

try {
    if ($exists) {
        execute_query("DELETE FROM product_reminders WHERE user_id = ? AND product_id = ?", [$user_id, $product_id]);
        $status = 'removed';
    } else {
        execute_query("INSERT INTO product_reminders (user_id, product_id) VALUES (?, ?)", [$user_id, $product_id]);
        $status = 'added';
    }
    echo json_encode(['success' => true, 'status' => $status]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
