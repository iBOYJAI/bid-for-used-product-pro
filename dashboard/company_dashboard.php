<?php
$page_title = 'Company Dashboard';
require_once __DIR__ . '/../includes/header.php';
require_login('company');

$company_id = get_company_id();

// Get statistics
$total_products = fetch_one("SELECT COUNT(*) as count FROM products WHERE company_id = ?", [$company_id])['count'];
$open_products = fetch_one("SELECT COUNT(*) as count FROM products WHERE company_id = ? AND status = 'open'", [$company_id])['count'];

// Get total bids on company's products
$total_bids = fetch_one("SELECT COUNT(*) as count FROM bids b 
                         INNER JOIN products p ON b.product_id = p.product_id 
                         WHERE p.company_id = ?", [$company_id])['count'];

$approved_bids = fetch_one("SELECT COUNT(*) as count FROM bids b 
                            INNER JOIN products p ON b.product_id = p.product_id 
                            WHERE p.company_id = ? AND b.bid_status = 'approved'", [$company_id])['count'];

// Get recent products
$recent_products = fetch_all("SELECT * FROM products WHERE company_id = ? ORDER BY created_at DESC LIMIT 5", [$company_id]);
?>

<div class="container">
    <h1 style="margin-bottom: 30px;">Company Dashboard</h1>
    
    <!-- Statistics -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total Products</div>
            <div class="stat-value"><?php echo $total_products; ?></div>
        </div>
        <div class="stat-card success">
            <div class="stat-label">Open Products</div>
            <div class="stat-value"><?php echo $open_products; ?></div>
        </div>
        <div class="stat-card warning">
            <div class="stat-label">Total Bids</div>
            <div class="stat-value"><?php echo $total_bids; ?></div>
        </div>
        <div class="stat-card danger">
            <div class="stat-label">Approved Bids</div>
            <div class="stat-value"><?php echo $approved_bids; ?></div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="card">
        <div class="card-header">
            <h2>Quick Actions</h2>
        </div>
        <div class="card-body" style="display: flex; gap: 15px; flex-wrap: wrap;">
            <a href="<?php echo APP_URL; ?>/company/post_product.php" class="btn btn-primary">Post New Product</a>
            <a href="<?php echo APP_URL; ?>/company/my_products.php" class="btn btn-secondary">View My Products</a>
            <a href="<?php echo APP_URL; ?>/company/all_bids.php" class="btn btn-warning">View All Bids</a>
        </div>
    </div>
    
    <!-- Recent Products -->
    <div class="card">
        <div class="card-header">
            <h2>Recent Products</h2>
        </div>
        <div class="card-body">
            <?php if (count($recent_products) > 0): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Base Price</th>
                            <th>Status</th>
                            <th>Bid End Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_products as $product): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                                <td><?php echo htmlspecialchars($product['category']); ?></td>
                                <td><?php echo format_currency($product['base_price']); ?></td>
                                <td>
                                    <span class="badge badge-<?php echo $product['status'] === 'open' ? 'success' : 'secondary'; ?>">
                                        <?php echo $product['status']; ?>
                                    </span>
                                </td>
                                <td><?php echo format_date($product['bid_end'], 'd M Y'); ?></td>
                                <td>
                                    <a href="<?php echo APP_URL; ?>/company/view_bids.php?id=<?php echo $product['product_id']; ?>" class="btn btn-sm btn-primary">View Bids</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="text-align: center; padding: 40px; color: var(--text-secondary);">
                    No products posted yet. <a href="<?php echo APP_URL; ?>/company/post_product.php">Post your first product</a>
                </p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
