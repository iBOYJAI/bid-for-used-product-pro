<?php
/**
 * Delete Product Handler
 */

require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/validation.php';

require_login('company');

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$company_id = get_company_id();

// Get product and verify ownership
$product = fetch_one("SELECT * FROM products WHERE product_id = ? AND company_id = ?", [$product_id, $company_id]);

if (!$product) {
    header('Location: my_products.php');
    exit;
}

try {
    begin_transaction();
    
    // Delete all bids for this product (CASCADE will handle this, but explicit is better)
    execute_query("DELETE FROM bids WHERE product_id = ?", [$product_id]);
    
    // Delete product
    execute_query("DELETE FROM products WHERE product_id = ?", [$product_id]);
    
    // Delete product image if exists
    if ($product['product_image']) {
        delete_file(PRODUCT_IMAGE_DIR . $product['product_image']);
    }
    
    commit_transaction();
    
    header('Location: my_products.php?success=deleted');
    exit;
    
} catch (Exception $e) {
    rollback_transaction();
    header('Location: my_products.php?error=database');
    exit;
}
?>
