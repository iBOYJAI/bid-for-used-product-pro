<?php
$page_title = 'Company Dashboard';
require_once __DIR__ . '/../includes/header.php';
require_login('company');

$user_id = get_user_id();
$company = fetch_one("SELECT * FROM companies WHERE user_id = ?", [$user_id]);

if (!$company) {
    echo '<div class="container"><div class="alert alert-error">Company profile not found.</div></div>';
    require_once __DIR__ . '/../includes/footer.php';
    exit;
}

$company_id = $company['company_id'];

// Stats
$my_products = fetch_one("SELECT COUNT(*) as count FROM products WHERE company_id = ?", [$company_id])['count'];
$active_products = fetch_one("SELECT COUNT(*) as count FROM products WHERE company_id = ? AND status = 'open'", [$company_id])['count'];
$received_bids = fetch_one("SELECT COUNT(*) as count FROM bids b JOIN products p ON b.product_id = p.product_id WHERE p.company_id = ?", [$company_id])['count'];
$pending_bids = fetch_one("SELECT COUNT(*) as count FROM bids b JOIN products p ON b.product_id = p.product_id WHERE p.company_id = ? AND b.bid_status = 'pending'", [$company_id])['count'];

?>

<div class="container" style="padding: 2rem 1.5rem;">
    <div class="flex-between" style="margin-bottom: 2rem;">
        <div>
            <h1 style="font-size: 1.75rem; margin-bottom: 0.5rem;">Dashboard</h1>
            <p style="color: var(--text-muted);">Welcome, <?php echo htmlspecialchars($company['company_name']); ?></p>
        </div>
        <a href="post_product.php" class="btn btn-primary">+ Post New Product</a>
    </div>

    <?php if ($company['verified_status'] === 'pending'): ?>
        <div class="alert alert-warning" style="background: #fffbeb; color: #92400e; padding: 1rem; border-radius: 0.5rem; margin-bottom: 2rem; border: 1px solid #fcd34d;">
            <strong>Verification Pending:</strong> Your account is currently under review. You may satisfy limited functionality until verified.
        </div>
    <?php endif; ?>

    <div class="grid-3" style="margin-bottom: 3rem; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
        <div class="card">
            <p style="color: var(--text-muted); font-size: 0.875rem;">Total Products</p>
            <p style="font-size: 2rem; font-weight: 700; color: var(--primary);"><?php echo $my_products; ?></p>
        </div>
        <div class="card">
            <p style="color: var(--text-muted); font-size: 0.875rem;">Active Auctions</p>
            <p style="font-size: 2rem; font-weight: 700; color: var(--secondary);"><?php echo $active_products; ?></p>
        </div>
        <div class="card">
            <p style="color: var(--text-muted); font-size: 0.875rem;">Total Bids Received</p>
            <p style="font-size: 2rem; font-weight: 700; color: var(--accent);"><?php echo $received_bids; ?></p>
        </div>
        <div class="card">
            <p style="color: var(--text-muted); font-size: 0.875rem;">Pending Actions</p>
            <p style="font-size: 2rem; font-weight: 700; color: #f59e0b;"><?php echo $pending_bids; ?></p>
            <p style="font-size: 0.75rem; color: var(--text-muted);">Bids matching approval</p>
        </div>
    </div>

    <div class="grid-3">
        <!-- Recent Products -->
        <div class="card" style="grid-column: span 2;">
            <div class="flex-between mb-4">
                <h3 style="font-size: 1.125rem;">Your Recent Products</h3>
                <a href="my_products.php" style="color: var(--primary); font-size: 0.875rem;">View All</a>
            </div>
            <?php
            $recent_products = fetch_all("SELECT * FROM products WHERE company_id = ? ORDER BY created_at DESC LIMIT 5", [$company_id]);
            if (empty($recent_products)): ?>
                <p style="color: var(--text-muted);">No products posted yet.</p>
            <?php else: ?>
                <div style="overflow-x: auto;">
                    <table style="width: 100%; text-align: left; border-collapse: collapse;">
                        <thead>
                            <tr style="border-bottom: 2px solid var(--border-color);">
                                <th style="padding: 0.75rem;">Product</th>
                                <th style="padding: 0.75rem;">Status</th>
                                <th style="padding: 0.75rem;">Bids</th>
                                <th style="padding: 0.75rem;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_products as $p):
                                $bids_count = fetch_one("SELECT COUNT(*) as count FROM bids WHERE product_id = ?", [$p['product_id']])['count'];
                            ?>
                                <tr style="border-bottom: 1px solid var(--border-color);">
                                    <td style="padding: 0.75rem; font-weight: 500;"><?php echo htmlspecialchars($p['product_name']); ?></td>
                                    <td style="padding: 0.75rem;">
                                        <span class="badge badge-<?php echo $p['status'] === 'open' ? 'success' : 'secondary'; ?>">
                                            <?php echo ucfirst($p['status']); ?>
                                        </span>
                                    </td>
                                    <td style="padding: 0.75rem;"><?php echo $bids_count; ?></td>
                                    <td style="padding: 0.75rem;">
                                        <a href="view_bids.php?id=<?php echo $p['product_id']; ?>" class="btn btn-sm btn-secondary">Manage</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- Quick Links -->
        <div class="card">
            <h3 style="font-size: 1.125rem; margin-bottom: 1rem;">Quick Links</h3>
            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                <a href="post_product.php" class="btn btn-secondary w-full" style="justify-content: flex-start;">Post Product</a>
                <a href="my_products.php" class="btn btn-secondary w-full" style="justify-content: flex-start;">Manage Inventory</a>
                <a href="../index.php" class="btn btn-secondary w-full" style="justify-content: flex-start;">View Live Site</a>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>