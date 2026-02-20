<?php
$page_title = 'My Products';
require_once __DIR__ . '/../includes/header.php';
require_login('company');

$company_id = get_company_id();
$products = fetch_all("SELECT * FROM products WHERE company_id = ? ORDER BY created_at DESC", [$company_id]);
?>

<div class="container" style="padding: 2rem 1.5rem;">
    <div class="flex-between" style="margin-bottom: 2rem;">
        <h1 style="font-size: 1.75rem;">My Products</h1>
        <a href="post_product.php" class="btn btn-primary">+ Post New Product</a>
    </div>

    <div class="card" style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Bid Duration</th>
                    <th>Stats</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $p):
                    $bids_count = fetch_one("SELECT COUNT(*) as count FROM bids WHERE product_id = ?", [$p['product_id']])['count'];
                ?>
                    <tr>
                        <td>
                            <div style="font-weight: 500;"><?php echo htmlspecialchars($p['product_name']); ?></div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);"><?php echo htmlspecialchars($p['model']); ?></div>
                        </td>
                        <td><?php echo htmlspecialchars($p['category']); ?></td>
                        <td style="font-weight: 600;"><?php echo format_currency($p['base_price']); ?></td>
                        <td>
                            <span class="badge badge-<?php echo $p['status'] === 'open' ? 'success' : 'secondary'; ?>">
                                <?php echo ucfirst($p['status']); ?>
                            </span>
                        </td>
                        <td style="font-size: 0.875rem; min-width: 150px;">
                            <span style="display: block; color: var(--text-muted);">Start: <?php echo format_date($p['bid_start']); ?></span>
                            <span style="display: block; font-weight: 500;">End: <?php echo format_date($p['bid_end']); ?></span>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <span class="badge badge-info"><?php echo $bids_count; ?> Bids</span>
                            </div>
                        </td>
                        <td>
                            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                <a href="view_bids.php?id=<?php echo $p['product_id']; ?>" class="btn btn-sm btn-primary">Bids</a>
                                <a href="edit_product.php?id=<?php echo $p['product_id']; ?>" class="btn btn-sm btn-secondary">Edit</a>
                                <a href="../product_details.php?id=<?php echo $p['product_id']; ?>" class="btn btn-sm btn-info" target="_blank">View</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php if (empty($products)): ?>
            <div style="padding: 3rem; text-align: center;">
                <p style="color: var(--text-muted); margin-bottom: 1rem;">You haven't posted any products yet.</p>
                <a href="post_product.php" class="btn btn-primary">Get Started</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>