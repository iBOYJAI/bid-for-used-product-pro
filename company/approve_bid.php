<?php
/**
 * Approve Bid Handler
 */

require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/session.php';

require_login('company');

$bid_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$company_id = get_company_id();

// Get bid details and verify ownership
$bid = fetch_one("SELECT b.*, p.product_id, p.company_id 
                  FROM bids b 
                  INNER JOIN products p ON b.product_id = p.product_id 
                  WHERE b.bid_id = ? AND p.company_id = ?", [$bid_id, $company_id]);

if (!$bid) {
    header('Location: my_products.php');
    exit;
}

$product_id = $bid['product_id'];

try {
    begin_transaction();
    
    // Approve this bid
    execute_query("UPDATE bids SET bid_status = 'approved' WHERE bid_id = ?", [$bid_id]);
    
    // Reject all other bids for this product
    execute_query("UPDATE bids SET bid_status = 'rejected' WHERE product_id = ? AND bid_id != ?", [$product_id, $bid_id]);
    
    // Close the product
    execute_query("UPDATE products SET status = 'closed' WHERE product_id = ?", [$product_id]);
    
    commit_transaction();
    
    header("Location: view_bids.php?id={$product_id}&success=approved");
    exit;
    
} catch (Exception $e) {
    rollback_transaction();
    header("Location: view_bids.php?id={$product_id}&error=database");
    exit;
}
?>
