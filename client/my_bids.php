<?php
$page_title = 'My Bids';
require_once __DIR__ . '/../includes/header.php';
require_login('client');

$user_id = get_user_id();

$sql = "SELECT b.*, p.product_name, p.product_image, p.status as product_status, p.bid_end 
        FROM bids b 
        JOIN products p ON b.product_id = p.product_id 
        WHERE b.client_id = ? 
        ORDER BY b.bid_time DESC";
$bids = fetch_all($sql, [$user_id]);
?>

<div class="container" style="padding: 2rem 1.5rem;">
    <div class="flex-between" style="margin-bottom: 2rem;">
        <h1 style="font-size: 1.75rem;">My Bidding History</h1>
        <a href="browse_products.php" class="btn btn-primary">Find New Products</a>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">Your bid has been placed successfully!</div>
    <?php endif; ?>

    <div class="card" style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>My Bid</th>
                    <th>Date Placed</th>
                    <th>Status</th>
                    <th>Auction Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bids as $bid): ?>
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 1rem;">
                                <?php if ($bid['product_image']): ?>
                                    <img src="<?php echo APP_URL; ?>/uploads/products/<?php echo $bid['product_image']; ?>"
                                        alt="Img"
                                        style="width: 40px; height: 40px; border-radius: 4px; object-fit: cover;">
                                <?php endif; ?>
                                <div>
                                    <div style="font-weight: 500;">
                                        <a href="../product_details.php?id=<?php echo $bid['product_id']; ?>">
                                            <?php echo htmlspecialchars($bid['product_name']); ?>
                                        </a>
                                    </div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);">
                                        Ends: <?php echo format_date($bid['bid_end']); ?>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td style="font-weight: 600; color: var(--primary);">
                            <?php echo format_currency($bid['bid_amount']); ?>
                        </td>
                        <td><?php echo format_date($bid['bid_time']); ?></td>
                        <td>
                            <span class="badge badge-<?php
                                                        echo $bid['bid_status'] === 'approved' ? 'success' : ($bid['bid_status'] === 'rejected' ? 'danger' : 'warning');
                                                        ?>">
                                <?php echo ucfirst($bid['bid_status']); ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-<?php echo $bid['product_status'] === 'open' ? 'info' : 'secondary'; ?>">
                                <?php echo ucfirst($bid['product_status']); ?>
                            </span>
                        </td>
                        <td>
                            <a href="../product_details.php?id=<?php echo $bid['product_id']; ?>" class="btn btn-sm btn-secondary">View</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php if (empty($bids)): ?>
            <div style="padding: 3rem; text-align: center;">
                <p style="color: var(--text-muted); margin-bottom: 1rem;">You haven't placed any bids yet.</p>
                <a href="browse_products.php" class="btn btn-primary">Start Bidding</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>