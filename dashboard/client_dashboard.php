<?php
$page_title = 'Client Dashboard';
require_once __DIR__ . '/../includes/header.php';
require_login('client');

$client_id = get_user_id();

// Get statistics
$available_products = fetch_one("SELECT COUNT(*) as count FROM products WHERE status = 'open' AND bid_end >= NOW()")['count'];
$my_bids_count = fetch_one("SELECT COUNT(*) as count FROM bids WHERE client_id = ?", [$client_id])['count'];
$approved_bids = fetch_one("SELECT COUNT(*) as count FROM bids WHERE client_id = ? AND bid_status = 'approved'", [$client_id])['count'];

// Check subscription status
$subscription = fetch_one("SELECT * FROM subscriptions WHERE client_id = ? AND status = 'active'", [$client_id]);
$is_subscribed = ($subscription !== false);

// Get recent products
$recent_products = fetch_all("SELECT p.*, c.company_name 
                              FROM products p 
                              INNER JOIN companies c ON p.company_id = c.company_id 
                              WHERE p.status = 'open' AND p.bid_end >= NOW() 
                              ORDER BY p.created_at DESC 
                              LIMIT 6", []);
?>

<div class="container">
    <h1 style="margin-bottom: 30px;">Client Dashboard</h1>
    
    <!-- Statistics -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Available Products</div>
            <div class="stat-value"><?php echo $available_products; ?></div>
        </div>
        <div class="stat-card warning">
            <div class="stat-label">My Bids</div>
            <div class="stat-value"><?php echo $my_bids_count; ?></div>
        </div>
        <div class="stat-card success">
            <div class="stat-label">Approved Bids</div>
            <div class="stat-value"><?php echo $approved_bids; ?></div>
        </div>
        <div class="stat-card <?php echo $is_subscribed ? 'success' : 'danger'; ?>">
            <div class="stat-label">Subscription</div>
            <div class="stat-value"><?php echo $is_subscribed ? 'Active' : 'Inactive'; ?></div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="card">
        <div class="card-header">
            <h2>Quick Actions</h2>
        </div>
        <div class="card-body" style="display: flex; gap: 15px; flex-wrap: wrap;">
            <a href="<?php echo APP_URL; ?>/client/browse_products.php" class="btn btn-primary">Browse Products</a>
            <a href="<?php echo APP_URL; ?>/client/my_bids.php" class="btn btn-secondary">My Bids</a>
            <?php if ($is_subscribed): ?>
                <a href="<?php echo APP_URL; ?>/client/unsubscribe.php" class="btn btn-danger">Unsubscribe</a>
            <?php else: ?>
                <a href="<?php echo APP_URL; ?>/client/subscribe.php" class="btn btn-success">Subscribe to Updates</a>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Recent Products -->
    <div class="card">
        <div class="card-header">
            <h2>Recent Available Products</h2>
        </div>
        <div class="card-body">
            <?php if (count($recent_products) > 0): ?>
                <div class="product-grid">
                    <?php foreach ($recent_products as $product): ?>
                        <div class="product-card">
                            <?php if ($product['product_image']): ?>
                                <img src="<?php echo APP_URL; ?>/uploads/products/<?php echo $product['product_image']; ?>" 
                                     alt="<?php echo htmlspecialchars($product['product_name']); ?>" 
                                     class="product-image">
                            <?php else: ?>
                                <div class="product-image" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 48px;">
                                    <?php echo strtoupper(substr($product['category'], 0, 1)); ?>
                                </div>
                            <?php endif; ?>
                            <div class="product-body">
                                <h3 class="product-title"><?php echo htmlspecialchars($product['product_name']); ?></h3>
                                <div class="product-info">
                                    <div class="product-info-item">
                                        <span class="product-info-label">Category:</span>
                                        <span class="product-info-value"><?php echo htmlspecialchars($product['category']); ?></span>
                                    </div>
                                    <div class="product-info-item">
                                        <span class="product-info-label">Base Price:</span>
                                        <span class="product-info-value"><?php echo format_currency($product['base_price']); ?></span>
                                    </div>
                                    <div class="product-info-item">
                                        <span class="product-info-label">Bid Ends:</span>
                                        <span class="product-info-value"><?php echo format_date($product['bid_end'], 'd M Y'); ?></span>
                                    </div>
                                </div>
                                <div class="product-footer">
                                    <a href="<?php echo APP_URL; ?>/client/product_details.php?id=<?php echo $product['product_id']; ?>" class="btn btn-sm btn-primary" style="flex: 1;">View Details</a>
                                    <a href="<?php echo APP_URL; ?>/client/place_bid.php?id=<?php echo $product['product_id']; ?>" class="btn btn-sm btn-success" style="flex: 1;">Place Bid</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p style="text-align: center; padding: 40px; color: var(--text-secondary);">
                    No products available at the moment.
                </p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
