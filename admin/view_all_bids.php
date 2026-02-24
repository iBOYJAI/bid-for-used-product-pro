<?php
$page_title = 'All Bids';
require_once __DIR__ . '/../includes/header.php';
require_login('admin');

$sql = "SELECT b.*, p.product_name, u.name as client_name, c.company_name 
        FROM bids b 
        JOIN products p ON b.product_id = p.product_id 
        JOIN users u ON b.client_id = u.user_id 
        JOIN companies c ON p.company_id = c.company_id 
        ORDER BY b.bid_time DESC";
$bids = fetch_all($sql);
?>

<div class="container" style="padding: 2rem 1.5rem;">
    <div class="flex-between" style="margin-bottom: 2rem;">
        <h1 style="font-size: 1.75rem;">All Bids System-wide</h1>
        <a href="dashboard.php" class="btn btn-secondary">&larr; Dashboard</a>
    </div>

    <div class="card" style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Bid ID</th>
                    <th>Product</th>
                    <th>Client</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Seller</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bids as $bid): ?>
                    <tr>
                        <td style="color: var(--text-muted);">#<?php echo $bid['bid_id']; ?></td>
                        <td>
                            <a href="../product_details.php?id=<?php echo $bid['product_id']; ?>" style="font-weight: 500; color: var(--text-main);">
                                <?php echo htmlspecialchars($bid['product_name']); ?>
                            </a>
                        </td>
                        <td><?php echo htmlspecialchars($bid['client_name']); ?></td>
                        <td style="font-weight: 600; color: var(--text-main);"><?php echo format_currency($bid['bid_amount']); ?></td>
                        <td>
                            <span class="badge badge-<?php
                                                        echo $bid['bid_status'] === 'approved' ? 'success' : ($bid['bid_status'] === 'rejected' ? 'danger' : 'warning');
                                                        ?>">
                                <?php echo ucfirst($bid['bid_status']); ?>
                            </span>
                        </td>
                        <td style="font-size: 0.875rem;"><?php echo format_date($bid['bid_time']); ?></td>
                        <td style="font-size: 0.875rem; color: var(--text-muted);"><?php echo htmlspecialchars($bid['company_name']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php if (empty($bids)): ?>
            <p style="padding: 1.5rem; text-align: center; color: var(--text-muted);">No bids found in the system.</p>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>