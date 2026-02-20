<?php
/**
 * Reject Bid Handler
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

// Reject this bid
execute_query("UPDATE bids SET bid_status = 'rejected' WHERE bid_id = ?", [$bid_id]);

header("Location: view_bids.php?id={$product_id}&success=rejected");
exit;
?>
