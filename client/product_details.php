<?php
$page_title = 'Product Details';
require_once __DIR__ . '/../includes/header.php';
require_login('client');

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$client_id = get_user_id();

// Get product details with company info
$product = fetch_one("SELECT p.*, c.company_name, c.owner_name, u.contact, u.email, u.address 
                      FROM products p 
                      INNER JOIN companies c ON p.company_id = c.company_id 
                      INNER JOIN users u ON c.user_id = u.user_id 
                      WHERE p.product_id = ?", [$product_id]);

if (!$product) {
    header('Location: browse_products.php');
    exit;
}

// Get bidding statistics
$bid_count = fetch_one("SELECT COUNT(*) as count FROM bids WHERE product_id = ?", [$product_id])['count'];
$highest_bid = fetch_one("SELECT MAX(bid_amount) as max_bid FROM bids WHERE product_id = ?", [$product_id])['max_bid'];

// Check if current user has bid
$my_bid = fetch_one("SELECT * FROM bids WHERE product_id = ? AND client_id = ?", [$product_id, $client_id]);

$is_active = is_bid_active($product['bid_start'], $product['bid_end']);
?>

<div class="container">
    <div class="card">
        <div class="card-header">
            <h2>Product Details</h2>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 30px;">
                <!-- Product Image -->
                <div>
                    <?php if ($product['product_image']): ?>
                        <img src="<?php echo APP_URL; ?>/uploads/products/<?php echo $product['product_image']; ?>" 
                             alt="<?php echo htmlspecialchars($product['product_name']); ?>" 
                             style="width: 100%; height: auto; border-radius: 12px; box-shadow: var(--shadow);">
                    <?php else: ?>
                        <div style="width: 100%; height: 300px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-size: 72px; font-weight: bold; box-shadow: var(--shadow);">
                            <?php echo strtoupper(substr($product['category'], 0, 1)); ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Action Buttons -->
                    <div style="margin-top: 20px; display: flex; flex-direction: column; gap: 10px;">
                        <?php if ($is_active && $product['status'] === 'open'): ?>
                            <a href="place_bid.php?id=<?php echo $product_id; ?>" class="btn btn-success btn-block">
                                <?php echo $my_bid ? 'Update My Bid' : 'Place Bid'; ?>
                            </a>
                        <?php else: ?>
                            <button class="btn btn-secondary btn-block" disabled>Bidding Closed</button>
                        <?php endif; ?>
                        <a href="browse_products.php" class="btn btn-secondary btn-block">Back to Products</a>
                    </div>
                </div>
                
                <!-- Product Information -->
                <div>
                    <h1 style="font-size: 28px; margin-bottom: 10px; color: var(--text-primary);">
                        <?php echo htmlspecialchars($product['product_name']); ?>
                    </h1>
                    
                    <p style="color: var(--text-secondary); margin-bottom: 20px;">
                        Posted by <strong><?php echo htmlspecialchars($product['company_name']); ?></strong>
                    </p>
                    
                    <div style="background: var(--light-bg); padding: 20px; border-radius: 8px; margin-bottom: 25px;">
                        <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 5px;">Base Price</div>
                        <div style="font-size: 32px; font-weight: 700; color: var(--danger-color);">
                            <?php echo format_currency($product['base_price']); ?>
                        </div>
                    </div>
                    
                    <div class="product-info" style="margin-bottom: 25px;">
                        <div class="product-info-item">
                            <span class="product-info-label">Category:</span>
                            <span class="product-info-value"><?php echo htmlspecialchars($product['category']); ?></span>
                        </div>
                        <div class="product-info-item">
                            <span class="product-info-label">Model:</span>
                            <span class="product-info-value"><?php echo htmlspecialchars($product['model']); ?></span>
                        </div>
                        <div class="product-info-item">
                            <span class="product-info-label">Year:</span>
                            <span class="product-info-value"><?php echo $product['year']; ?></span>
                        </div>
                        <?php if ($product['chassis_no']): ?>
                            <div class="product-info-item">
                                <span class="product-info-label">Chassis No:</span>
                                <span class="product-info-value"><?php echo htmlspecialchars($product['chassis_no']); ?></span>
                            </div>
                        <?php endif; ?>
                        <div class="product-info-item">
                            <span class="product-info-label">Running Duration:</span>
                            <span class="product-info-value"><?php echo htmlspecialchars($product['running_duration']); ?></span>
                        </div>
                        <div class="product-info-item">
                            <span class="product-info-label">Owner Details:</span>
                            <span class="product-info-value"><?php echo htmlspecialchars($product['owner_details']); ?></span>
                        </div>
                        <div class="product-info-item">
                            <span class="product-info-label">Status:</span>
                            <span class="badge badge-<?php echo $product['status'] === 'open' ? 'success' : 'secondary'; ?>">
                                <?php echo $product['status']; ?>
                            </span>
                        </div>
                    </div>
                    
                    <!-- Bidding Information -->
                    <div style="background: #dbeafe; padding: 20px; border-radius: 8px; margin-bottom: 25px;">
                        <h3 style="font-size: 18px; margin-bottom: 15px; color: #1e40af;">Bidding Information</h3>
                        <div class="product-info">
                            <div class="product-info-item">
                                <span class="product-info-label">Bid Start:</span>
                                <span class="product-info-value"><?php echo format_date($product['bid_start']); ?></span>
                            </div>
                            <div class="product-info-item">
                                <span class="product-info-label">Bid End:</span>
                                <span class="product-info-value"><?php echo format_date($product['bid_end']); ?></span>
                            </div>
                            <div class="product-info-item">
                                <span class="product-info-label">Total Bids:</span>
                                <span class="badge badge-info"><?php echo $bid_count; ?></span>
                            </div>
                            <?php if ($highest_bid): ?>
                                <div class="product-info-item">
                                    <span class="product-info-label">Highest Bid:</span>
                                    <span class="product-info-value" style="color: var(--success-color); font-weight: 700;">
                                        <?php echo format_currency($highest_bid); ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- My Bid Status -->
                    <?php if ($my_bid): ?>
                        <div style="background: #d1fae5; padding: 20px; border-radius: 8px; margin-bottom: 25px;">
                            <h3 style="font-size: 18px; margin-bottom: 15px; color: #065f46;">Your Bid</h3>
                            <div class="product-info">
                                <div class="product-info-item">
                                    <span class="product-info-label">Bid Amount:</span>
                                    <span class="product-info-value" style="font-weight: 700;"><?php echo format_currency($my_bid['bid_amount']); ?></span>
                                </div>
                                <div class="product-info-item">
                                    <span class="product-info-label">Status:</span>
                                    <span class="badge badge-<?php 
                                        echo $my_bid['bid_status'] === 'approved' ? 'success' : 
                                             ($my_bid['bid_status'] === 'rejected' ? 'danger' : 'warning'); 
                                    ?>">
                                        <?php echo $my_bid['bid_status']; ?>
                                    </span>
                                </div>
                                <div class="product-info-item">
                                    <span class="product-info-label">Bid Date:</span>
                                    <span class="product-info-value"><?php echo format_date($my_bid['bid_time']); ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Company Contact -->
                    <div style="border-top: 2px solid var(--border-color); padding-top: 20px;">
                        <h3 style="font-size: 18px; margin-bottom: 15px;">Company Contact</h3>
                        <div class="product-info">
                            <div class="product-info-item">
                                <span class="product-info-label">Company:</span>
                                <span class="product-info-value"><?php echo htmlspecialchars($product['company_name']); ?></span>
                            </div>
                            <div class="product-info-item">
                                <span class="product-info-label">Owner:</span>
                                <span class="product-info-value"><?php echo htmlspecialchars($product['owner_name']); ?></span>
                            </div>
                            <div class="product-info-item">
                                <span class="product-info-label">Contact:</span>
                                <span class="product-info-value"><?php echo htmlspecialchars($product['contact']); ?></span>
                            </div>
                            <div class="product-info-item">
                                <span class="product-info-label">Email:</span>
                                <span class="product-info-value"><?php echo htmlspecialchars($product['email']); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
