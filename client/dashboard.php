<?php
$page_title = 'My Dashboard';
require_once __DIR__ . '/../includes/header.php';
require_login('client');

$user_id = get_user_id();

// Stats
$active_bids = fetch_one("SELECT COUNT(*) as count FROM bids b JOIN products p ON b.product_id = p.product_id WHERE b.client_id = ? AND p.status = 'open'", [$user_id])['count'];
$won_bids = fetch_one("SELECT COUNT(*) as count FROM bids WHERE client_id = ? AND bid_status = 'approved'", [$user_id])['count'];
$total_bids = fetch_one("SELECT COUNT(*) as count FROM bids WHERE client_id = ?", [$user_id])['count'];

?>

<div class="container" style="padding: 2rem 1.5rem;">
    <div class="flex-between" style="margin-bottom: 2rem;">
        <h1 style="font-size: 1.75rem;">My Dashboard</h1>
        <a href="../products.php" class="btn btn-primary">Browse New Products</a>
    </div>

    <div class="grid-3" style="margin-bottom: 3rem;">
        <div class="card">
            <p style="color: var(--text-muted); font-size: 0.875rem;">Active Bids</p>
            <p style="font-size: 2rem; font-weight: 700; color: var(--primary);"><?php echo $active_bids; ?></p>
        </div>
        <div class="card">
            <p style="color: var(--text-muted); font-size: 0.875rem;">Won Auctions</p>
            <p style="font-size: 2rem; font-weight: 700; color: var(--success-color, #10b981);"><?php echo $won_bids; ?></p>
        </div>
        <div class="card">
            <p style="color: var(--text-muted); font-size: 0.875rem;">Total History</p>
            <p style="font-size: 2rem; font-weight: 700; color: var(--text-main);"><?php echo $total_bids; ?></p>
        </div>
    </div>

    <div class="grid-3" style="grid-template-columns: 2fr 1fr;">
        <div class="card">
            <div class="flex-between mb-4">
                <h3 style="font-size: 1.125rem;">My Recent Bids</h3>
                <a href="my_bids.php" style="color: var(--primary); font-size: 0.875rem;">View Full History</a>
            </div>

            <?php
            $recent_bids = fetch_all("
                SELECT b.*, p.product_name, p.status as product_status, p.bid_end 
                FROM bids b 
                JOIN products p ON b.product_id = p.product_id 
                WHERE b.client_id = ? 
                ORDER BY b.bid_date DESC 
                LIMIT 5
            ", [$user_id]);

            if (empty($recent_bids)): ?>
                <p style="color: var(--text-muted);">You haven't placed any bids yet.</p>
                <a href="../products.php" class="btn btn-primary mt-4">Start Bidding</a>
            <?php else: ?>
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <?php foreach ($recent_bids as $bid): ?>
                        <div style="border-bottom: 1px solid var(--border-color); padding-bottom: 1rem;">
                            <div class="flex-between">
                                <h4 style="font-size: 1rem; margin-bottom: 0.25rem;">
                                    <a href="../product_details.php?id=<?php echo $bid['product_id']; ?>">
                                        <?php echo htmlspecialchars($bid['product_name']); ?>
                                    </a>
                                </h4>
                                <span class="badge badge-<?php
                                                            echo $bid['bid_status'] === 'approved' ? 'success' : ($bid['bid_status'] === 'rejected' ? 'danger' : 'warning');
                                                            ?>">
                                    <?php echo ucfirst($bid['bid_status']); ?>
                                </span>
                            </div>
                            <div class="flex-between" style="font-size: 0.875rem; color: var(--text-muted);">
                                <span>My Bid: <strong><?php echo format_currency($bid['bid_amount']); ?></strong></span>
                                <span><?php echo format_date($bid['bid_date']); ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="card">
            <h3 style="font-size: 1.125rem; margin-bottom: 1rem;">Account</h3>
            <a href="my_bids.php" class="btn btn-secondary w-full mb-4">My Bids</a>
            <div class="alert alert-info" style="background: #eff6ff; padding: 1rem; border-radius: 0.5rem; font-size: 0.875rem;">
                For profile updates, please <a href="../pages/contact.php" style="color: inherit; text-decoration: underline;">contact support</a>.
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>