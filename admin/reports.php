<?php
$page_title = 'System Reports';
require_once __DIR__ . '/../includes/header.php';
require_login('admin');

// Simple helper for stats
function get_monthly_bids()
{
    // Returns array of last 6 months counts
    // Mock data for demo if DB query is complex to write inline without testing
    // But let's try a real query
    return fetch_all("
        SELECT DATE_FORMAT(bid_time, '%Y-%m') as month, COUNT(*) as count, SUM(bid_amount) as amount 
        FROM bids 
        GROUP BY month 
        ORDER BY month DESC 
        LIMIT 6
    ");
}
$monthly_stats = get_monthly_bids();
?>

<div class="container" style="padding: 2rem 1.5rem;">
    <div class="flex-between" style="margin-bottom: 2rem;">
        <div>
            <h1 style="font-size: 1.75rem;">Reports & Analytics</h1>
            <p style="color: var(--text-muted);">View system performance and download logs</p>
        </div>
        <button class="btn btn-primary" onclick="window.print()">Download / Print Report</button>
    </div>

    <!-- Charts Section (Visuals) -->
    <div class="grid-2" style="margin-bottom: 3rem; gap: 2rem;">
        <div class="card">
            <h3 style="margin-bottom: 1rem;">Revenue & Activity</h3>
            <div style="height: 200px; display: flex; align-items: flex-end; justify-content: space-around; padding-bottom: 10px; border-bottom: 1px solid #e2e8f0;">
                <!-- Mock Bars if no JS -->
                <?php foreach (array_reverse($monthly_stats) as $stat):
                    $height = max(10, min(100, ($stat['count'] * 5))) . '%';
                ?>
                    <div style="text-align: center; width: 100%;">
                        <div style="height: <?php echo $height; ?>; background: var(--primary); width: 30px; margin: 0 auto; border-radius: 4px 4px 0 0;"></div>
                        <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 5px;"><?php echo date('M', strtotime($stat['month'])); ?></p>
                    </div>
                <?php endforeach; ?>
                <?php if (empty($monthly_stats)): ?>
                    <p style="width: 100%; text-align: center; color: var(--text-muted);">No data available yet</p>
                <?php endif; ?>
            </div>
            <p style="text-align: center; margin-top: 1rem; color: var(--text-muted); font-size: 0.875rem;">Bid Activity (Last 6 Months)</p>
        </div>

        <div class="card text-center" style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
            <img src="<?php echo APP_URL; ?>/assets/images/Notion-Resources/Office-Club/Regular/png/oc-money-profits.png" alt="Revenue" style="height: 150px; margin-bottom: 1rem;">
            <h3>Total System Value</h3>
            <p style="font-size: 2.5rem; font-weight: 800; color: #10b981;">
                <?php
                $total_value = fetch_one("SELECT SUM(base_price) as total FROM products")['total'] ?? 0;
                echo format_currency($total_value);
                ?>
            </p>
            <p style="color: var(--text-muted);">Aggregate Product Value</p>
        </div>
    </div>

    <!-- Data Tables -->
    <div class="card">
        <h3 style="margin-bottom: 1.5rem;">Recent Transactions / Bids</h3>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8fafc; text-align: left;">
                        <th style="padding: 1rem; border-bottom: 2px solid #e2e8f0;">Date</th>
                        <th style="padding: 1rem; border-bottom: 2px solid #e2e8f0;">Product</th>
                        <th style="padding: 1rem; border-bottom: 2px solid #e2e8f0;">User</th>
                        <th style="padding: 1rem; border-bottom: 2px solid #e2e8f0;">Amount</th>
                        <th style="padding: 1rem; border-bottom: 2px solid #e2e8f0;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $bids = fetch_all("
                        SELECT b.*, p.product_name, u.name as client_name 
                        FROM bids b 
                        JOIN products p ON b.product_id = p.product_id 
                        JOIN users u ON b.client_id = u.user_id 
                        ORDER BY b.bid_time DESC 
                        LIMIT 10
                    ");
                    foreach ($bids as $bid): ?>
                        <tr>
                            <td style="padding: 1rem; border-bottom: 1px solid #e2e8f0;"><?php echo format_date($bid['bid_time']); ?></td>
                            <td style="padding: 1rem; border-bottom: 1px solid #e2e8f0;"><?php echo htmlspecialchars($bid['product_name']); ?></td>
                            <td style="padding: 1rem; border-bottom: 1px solid #e2e8f0;"><?php echo htmlspecialchars($bid['client_name']); ?></td>
                            <td style="padding: 1rem; border-bottom: 1px solid #e2e8f0; font-weight: 600;"><?php echo format_currency($bid['bid_amount']); ?></td>
                            <td style="padding: 1rem; border-bottom: 1px solid #e2e8f0;">
                                <span class="badge badge-<?php echo $bid['bid_status'] === 'approved' ? 'success' : 'secondary'; ?>">
                                    <?php echo ucfirst($bid['bid_status']); ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>