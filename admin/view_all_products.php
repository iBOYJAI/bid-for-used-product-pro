<?php
$page_title = 'All Products';
require_once __DIR__ . '/../includes/header.php';
require_login('admin');

$sql = "SELECT p.*, c.company_name 
        FROM products p 
        JOIN companies c ON p.company_id = c.company_id 
        ORDER BY p.created_at DESC";
$products = fetch_all($sql);
?>

<div class="container" style="padding: 2rem 1.5rem;">
    <div class="flex-between" style="margin-bottom: 2rem;">
        <h1 style="font-size: 1.75rem;">All Products</h1>
        <a href="dashboard.php" class="btn btn-secondary">&larr; Dashboard</a>
    </div>

    <div class="card" style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Company</th>
                    <th>Category</th>
                    <th>Base Price</th>
                    <th>Status</th>
                    <th>Bids End</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $p): ?>
                    <tr>
                        <td>
                            <div style="font-weight: 500;">
                                <a href="../product_details.php?id=<?php echo $p['product_id']; ?>" style="color: var(--primary);">
                                    <?php echo htmlspecialchars($p['product_name']); ?>
                                </a>
                            </div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);"><?php echo htmlspecialchars($p['model']); ?> (<?php echo $p['year']; ?>)</div>
                        </td>
                        <td><?php echo htmlspecialchars($p['company_name']); ?></td>
                        <td><?php echo htmlspecialchars($p['category']); ?></td>
                        <td style="font-weight: 600;"><?php echo format_currency($p['base_price']); ?></td>
                        <td>
                            <span class="badge badge-<?php echo $p['status'] === 'open' ? 'success' : 'secondary'; ?>">
                                <?php echo ucfirst($p['status']); ?>
                            </span>
                        </td>
                        <td style="font-size: 0.875rem;"><?php echo format_date($p['bid_end']); ?></td>
                        <td>
                            <a href="../product_details.php?id=<?php echo $p['product_id']; ?>" class="btn btn-sm btn-secondary">View</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php if (empty($products)): ?>
            <p style="padding: 1.5rem; text-align: center; color: var(--text-muted);">No products listed yet.</p>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>