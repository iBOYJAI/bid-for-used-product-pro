<?php
/**
 * Place Bid Process Handler
 */

require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/validation.php';

require_login('client');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: browse_products.php');
    exit;
}

$product_id = (int)$_POST['product_id'];
$client_id = get_user_id();
$bid_amount = (float)$_POST['bid_amount'];
$comments = sanitize_input($_POST['comments'] ?? '');

// Get product details
$product = fetch_one("SELECT * FROM products WHERE product_id = ? AND status = 'open'", [$product_id]);

if (!$product) {
    header('Location: browse_products.php');
    exit;
}

// Validate bid amount
if ($bid_amount <= $product['base_price']) {
    header("Location: place_bid.php?id={$product_id}&error=low_bid");
    exit;
}

// Check if bidding is still active
if (!is_bid_active($product['bid_start'], $product['bid_end'])) {
    header("Location: product_details.php?id={$product_id}&error=closed");
    exit;
}

// Check if user has already placed a bid
$existing_bid = fetch_one("SELECT * FROM bids WHERE product_id = ? AND client_id = ?", [$product_id, $client_id]);

try {
    if ($existing_bid) {
        // Update existing bid (only if still pending)
        if ($existing_bid['bid_status'] === 'pending') {
            execute_query("UPDATE bids SET bid_amount = ?, comments = ? WHERE bid_id = ?", 
                         [$bid_amount, $comments, $existing_bid['bid_id']]);
        } else {
            header("Location: my_bids.php?error=already_processed");
            exit;
        }
    } else {
        // Insert new bid
        $sql = "INSERT INTO bids (product_id, client_id, bid_amount, comments, bid_status) 
                VALUES (?, ?, ?, ?, 'pending')";
        execute_query($sql, [$product_id, $client_id, $bid_amount, $comments]);
    }
    
    header("Location: my_bids.php?success=bid_placed");
    exit;
    
} catch (Exception $e) {
    header("Location: place_bid.php?id={$product_id}&error=database");
    exit;
}
?>
