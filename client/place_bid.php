<?php
$page_title = 'Place Bid';
require_once __DIR__ . '/../includes/header.php';
require_login('client');

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$user_id = get_user_id();

// Fetch product details
$product = fetch_one("SELECT * FROM products WHERE product_id = ?", [$product_id]);

if (!$product) {
    echo '<div class="container"><div class="alert alert-error">Product not found.</div></div>';
    require_once __DIR__ . '/../includes/footer.php';
    exit;
}

// Check if open
if ($product['status'] !== 'open' || !is_bid_active($product['bid_start'], $product['bid_end'])) {
    echo '<div class="container"><div class="alert alert-warning">This auction is closed.</div><a href="browse_products.php" class="btn btn-primary">Back</a></div>';
    require_once __DIR__ . '/../includes/footer.php';
    exit;
}

// Get highest bid
$highest_bid = fetch_one("SELECT MAX(bid_amount) as max_bid FROM bids WHERE product_id = ?", [$product_id])['max_bid'];
$min_bid = $highest_bid ? $highest_bid + 100 : $product['base_price'];

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = filter_var($_POST['amount'], FILTER_VALIDATE_FLOAT);

    if (!$amount || $amount < $min_bid) {
        $error = "Bid amount must be at least " . format_currency($min_bid);
    } else {
        // Place bid
        $sql = "INSERT INTO bids (product_id, client_id, bid_amount, bid_status) VALUES (?, ?, ?, 'pending')";
        if (execute_query($sql, [$product_id, $user_id, $amount])) {
            // Send Notification to Company Owner
            include_once __DIR__ . '/../includes/notifications.php';
            $company_owner_id = fetch_one("SELECT user_id FROM companies WHERE company_id = ?", [$product['company_id']])['user_id'];
            if ($company_owner_id) {
                create_notification(
                    $company_owner_id,
                    'New Bid Received',
                    "A new bid of " . format_currency($amount) . " was placed on your product: " . $product['product_name'],
                    'company'
                );
            }

            header("Location: my_bids.php?success=bid_placed");
            exit;
        } else {
            $error = "Failed to place bid. Please try again.";
        }
    }
}
?>

<div class="container" style="padding: 2rem 1.5rem;">
    <div class="card" style="max-width: 600px; margin: 2rem auto;">
        <div style="text-align: center; margin-bottom: 2rem;">
            <h1 style="font-size: 1.75rem; margin-bottom: 0.5rem;">Place Your Bid</h1>
            <p style="color: var(--text-muted);">
                For <strong><?php echo htmlspecialchars($product['product_name']); ?></strong>
            </p>
        </div>

        <div style="background: var(--bg-body); padding: 1.5rem; border-radius: 0.5rem; margin-bottom: 2rem; border: 1px solid var(--border-color);">
            <div class="flex-between" style="margin-bottom: 0.5rem;">
                <span>Base Price:</span>
                <strong><?php echo format_currency($product['base_price']); ?></strong>
            </div>
            <div class="flex-between" style="margin-bottom: 0.5rem;">
                <span>Current Highest Bid:</span>
                <strong style="color: var(--primary);"><?php echo $highest_bid ? format_currency($highest_bid) : 'No bids yet'; ?></strong>
            </div>
            <div class="flex-between" style="margin-top: 1rem; padding-top: 1rem; border-top: 1px dashed var(--border-color);">
                <span>Minimum Required Bid:</span>
                <strong style="color: var(--success-color, #10b981);"><?php echo format_currency($min_bid); ?></strong>
            </div>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label class="form-label" style="font-size: 1.2rem;">Your Bid Amount (₹)</label>
                <input type="number" name="amount" class="form-control" required min="<?php echo $min_bid; ?>" step="100" value="<?php echo $min_bid; ?>" style="font-size: 1.5rem; padding: 1rem;">
                <p style="color: var(--text-muted); font-size: 0.875rem; margin-top: 0.5rem;">
                    Enter a value higher than <?php echo format_currency($min_bid); ?>
                </p>
            </div>

            <div class="grid-3" style="grid-template-columns: 1fr 1fr; gap: 1rem;">
                <a href="browse_products.php" class="btn btn-secondary w-full">Cancel</a>
                <button type="submit" class="btn btn-primary w-full">Confirm Bid</button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>