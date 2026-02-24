<?php
$page_title = 'View Bids';
require_once __DIR__ . '/../includes/header.php';
require_login('company');

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$company_id = get_company_id();

// Verify ownership
$product = fetch_one("SELECT * FROM products WHERE product_id = ? AND company_id = ?", [$product_id, $company_id]);

if (!$product) {
    echo '<div class="container"><div class="alert alert-error">Product not found or access denied.</div></div>';
    require_once __DIR__ . '/../includes/footer.php';
    exit;
}

// Handle actions
if (isset($_GET['action']) && isset($_GET['bid_id'])) {
    $action = $_GET['action'];
    $bid_id = intval($_GET['bid_id']);
    $status = ($action === 'approve') ? 'approved' : 'rejected';

    $update = execute_query("UPDATE bids SET bid_status = ? WHERE bid_id = ? AND product_id = ?", [$status, $bid_id, $product_id]);

    // If approved, verify close other bids? (Optional custom logic, keeping simple for now)
    // If approved, maybe close the product?
    // For now, just update status.

    // Notify the Bidder (Client)
    include_once __DIR__ . '/../includes/notifications.php';
    $bid_details = fetch_one("SELECT client_id, bid_amount FROM bids WHERE bid_id = ?", [$bid_id]);
    if ($bid_details) {
        $msg = ($status === 'approved')
            ? "Congratulations! Your bid of " . format_currency($bid_details['bid_amount']) . " for " . $product['product_name'] . " was APPROVED!"
            : "Update: Your bid for " . $product['product_name'] . " was Rejected.";

        create_notification($bid_details['client_id'], 'Bid ' . ucfirst($status), $msg, 'client');
    }

    header("Location: view_bids.php?id=$product_id&success=$status");
    exit;
}

$bids = fetch_all("SELECT b.*, u.name as client_name, u.email as client_email, u.contact as client_contact 
                   FROM bids b 
                   JOIN users u ON b.client_id = u.user_id 
                   WHERE b.product_id = ? 
                   ORDER BY b.bid_amount DESC", [$product_id]);
?>

<div class="container" style="padding: 2rem 1.5rem;">
    <div class="flex-between" style="margin-bottom: 2rem;">
        <div>
            <h1 style="font-size: 1.75rem;">Bids for <?php echo htmlspecialchars($product['product_name']); ?></h1>
            <p style="color: var(--text-muted); margin-top: 0.25rem;">
                Base Price: <strong><?php echo format_currency($product['base_price']); ?></strong> •
                Status: <span class="badge badge-<?php echo $product['status'] === 'open' ? 'success' : 'secondary'; ?>"><?php echo ucfirst($product['status']); ?></span>
            </p>
        </div>
        <a href="my_products.php" class="btn btn-secondary">&larr; Back to Products</a>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">Bid status updated to: <?php echo htmlspecialchars($_GET['success']); ?></div>
    <?php endif; ?>

    <div class="card" style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Bidder</th>
                    <th>Bid Amount</th>
                    <th>Contact Info</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bids as $bid): ?>
                    <tr style="<?php echo $bid['bid_status'] === 'approved' ? 'background: #f0fdf4;' : ''; ?>">
                        <td>
                            <div style="font-weight: 500;"><?php echo htmlspecialchars($bid['client_name']); ?></div>
                        </td>
                        <td style="font-weight: 700; font-size: 1.1rem; color: var(--primary);">
                            <?php echo format_currency($bid['bid_amount']); ?>
                        </td>
                        <td>
                            <div style="font-size: 0.875rem;">
                                <?php echo htmlspecialchars($bid['client_email']); ?><br>
                                <?php echo htmlspecialchars($bid['client_contact']); ?>
                            </div>
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
                            <?php if ($bid['bid_status'] === 'pending'): ?>
                                <div style="display: flex; gap: 0.5rem;">
                                    <a href="view_bids.php?id=<?php echo $product_id; ?>&bid_id=<?php echo $bid['bid_id']; ?>&action=approve"
                                        class="btn btn-sm btn-success"
                                        onclick="return confirm('Approve this bid? This may reject others or close the auction.')">
                                        Approve
                                    </a>
                                    <a href="view_bids.php?id=<?php echo $product_id; ?>&bid_id=<?php echo $bid['bid_id']; ?>&action=reject"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('Reject this bid?')">
                                        Reject
                                    </a>
                                </div>
                            <?php else: ?>
                                <span style="font-size: 0.875rem; color: var(--text-muted);">Processed</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php if (empty($bids)): ?>
            <p style="padding: 1.5rem; text-align: center; color: var(--text-muted);">No bids received yet.</p>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>