<?php
$page_title = 'Product Details';
require_once __DIR__ . '/includes/header.php';

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$current_user_id = is_logged_in() ? get_user_id() : null;
$current_role = is_logged_in() ? get_user_role() : null;

// Get product details (LEFT JOIN so product still shows if company/user data is missing)
$product = fetch_one("SELECT p.*, c.company_name, c.owner_name, u.contact, u.email, u.address 
                      FROM products p 
                      LEFT JOIN companies c ON p.company_id = c.company_id 
                      LEFT JOIN users u ON c.user_id = u.user_id 
                      WHERE p.product_id = ?", [$product_id]);

if (!$product) {
    echo '<div class="container" style="padding: 4rem;"><div class="card text-center" style="padding: 3rem;">
        <div style="font-size: 3rem; opacity: 0.5;">🚫</div>
        <h2 style="margin-top: 1rem;">Product not found</h2>
        <a href="products.php" class="btn btn-primary mt-4">Browse Products</a>
    </div></div>';
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

// Get stats
$bid_count = fetch_one("SELECT COUNT(*) as count FROM bids WHERE product_id = ?", [$product_id])['count'];
$highest_bid = fetch_one("SELECT MAX(bid_amount) as max_bid FROM bids WHERE product_id = ?", [$product_id])['max_bid'];

// Check user bid if client
$my_bid = null;
if ($current_role === 'client') {
    $my_bid = fetch_one("SELECT * FROM bids WHERE product_id = ? AND client_id = ?", [$product_id, $current_user_id]);
}

$is_active = is_bid_active($product['bid_start'], $product['bid_end']);
$image_url = $product['product_image'] ? APP_URL . '/uploads/products/' . $product['product_image'] : null;
$gallery = fetch_all("SELECT image_path FROM product_gallery WHERE product_id = ?", [$product_id]);
// If no main image but user uploaded gallery images, use first gallery image as main (only user uploads shown)
if (!$image_url && !empty($gallery)) {
    $image_url = APP_URL . '/uploads/products/' . $gallery[0]['image_path'];
}
?>

<div style="background: var(--bg-body); min-height: 100vh; padding: 2rem 0;">
    <div class="container">
        <!-- Breadcrumb -->
        <div style="margin-bottom: 2rem; color: var(--text-muted); font-size: 0.875rem;">
            <a href="index.php" style="color: var(--text-muted);">Home</a> /
            <a href="products.php" style="color: var(--text-muted);">Products</a> /
            <span style="color: var(--text-main); font-weight: 500;"><?php echo htmlspecialchars($product['product_name']); ?></span>
        </div>

        <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 3rem;">
            <!-- Left Column: Media & Details -->
            <div>
                <!-- Main Image -->
                <!-- Main Image & Gallery -->
                <div style="border-radius: 1rem; overflow: hidden; background: white; box-shadow: var(--shadow-sm); margin-bottom: 1rem; border: 1px solid var(--border-color);">
                    <?php if ($image_url): ?>
                        <img id="mainImage" src="<?php echo $image_url; ?>" alt="" style="width: 100%; height: 400px; object-fit: contain; display: block; background: #f8fafc;">
                    <?php else: ?>
                        <div id="mainImage" style="width: 100%; height: 400px; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: var(--text-muted); font-size: 0.95rem;">No image uploaded</div>
                    <?php endif; ?>
                </div>

                <!-- Gallery Thumbnails: only user-uploaded gallery images -->
                <?php if (!empty($gallery)): ?>
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(80px, 1fr)); gap: 0.5rem; margin-bottom: 2rem;">
                        <?php foreach ($gallery as $img):
                            $thumb_url = APP_URL . '/uploads/products/' . $img['image_path'];
                        ?>
                            <div onclick="changeImage('<?php echo $thumb_url; ?>')" style="cursor: pointer; border-radius: 0.5rem; overflow: hidden; border: 2px solid transparent; height: 60px;" onmouseover="this.style.borderColor='var(--primary)'" onmouseout="this.style.borderColor='transparent'">
                                <img src="<?php echo $thumb_url; ?>" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <script>
                    function changeImage(src) {
                        document.getElementById('mainImage').src = src;
                    }
                </script>

                <!-- Product Specifications -->
                <div class="card" style="margin-bottom: 2rem;">
                    <h3 style="margin-bottom: 1.5rem; font-size: 1.25rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 1rem;">Vehicle Specifications</h3>
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 2rem;">
                        <div>
                            <p style="color: var(--text-muted); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.25rem;">Make & Model</p>
                            <p style="font-weight: 600; font-size: 1.1rem;"><?php echo htmlspecialchars($product['model'] ?? '—'); ?></p>
                        </div>
                        <div>
                            <p style="color: var(--text-muted); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.25rem;">Category</p>
                            <p style="font-weight: 600; font-size: 1.1rem;"><?php echo htmlspecialchars($product['category'] ?? '—'); ?></p>
                        </div>
                        <div>
                            <p style="color: var(--text-muted); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.25rem;">Manufacturing Year</p>
                            <p style="font-weight: 600; font-size: 1.1rem;"><?php echo $product['year'] ?? '—'; ?></p>
                        </div>
                        <div>
                            <p style="color: var(--text-muted); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.25rem;">Usage / Mileage</p>
                            <p style="font-weight: 600; font-size: 1.1rem;"><?php echo htmlspecialchars($product['running_duration'] ?? '—'); ?></p>
                        </div>
                        <div style="grid-column: span 2;">
                            <p style="color: var(--text-muted); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.25rem;">Chassis Number</p>
                            <p style="font-family: monospace; background: #f1f5f9; padding: 0.5rem; border-radius: 0.25rem; display: inline-block;"><?php echo htmlspecialchars($product['chassis_no'] ?? '—'); ?></p>
                        </div>
                        <div style="grid-column: span 2;">
                            <p style="color: var(--text-muted); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.25rem;">Ownership & History</p>
                            <p style="line-height: 1.6;"><?php echo nl2br(htmlspecialchars($product['owner_details'] ?? '')); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Seller Info -->
                <div class="card" style="position: relative; overflow: hidden;">
                    <h3 style="margin-bottom: 1.5rem; font-size: 1.25rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 1rem;">Seller Information</h3>
                    <div style="display: flex; gap: 1.5rem; align-items: center; position: relative; z-index: 2;">
                        <div style="width: 64px; height: 64px; border-radius: 50%; background: #fff7ed; padding: 10px; display: flex; align-items: center; justify-content: center; border: 2px solid #ffedd5;">
                            <img src="<?php echo APP_URL; ?>/assets/images/Notion-Resources/Office-Club/Regular/png/oc-handshake.png" alt="Trusted" style="width: 100%; height: auto;">
                        </div>
                        <div>
                            <h4 style="margin-bottom: 0.25rem; font-size: 1.1rem;"><?php echo htmlspecialchars($product['company_name'] ?? 'N/A'); ?></h4>
                            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 0.5rem;">
                                Owner: <?php echo htmlspecialchars($product['owner_name'] ?? 'N/A'); ?>
                            </p>
                            <div style="display: flex; gap: 0.5rem;">
                                <span class="badge" style="background: #dcfce7; color: #166534;">Verified</span>
                                <span class="badge" style="background: #f1f5f9; color: #475569;">GST Reg.</span>
                            </div>
                        </div>
                    </div>
                    <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px dashed #e2e8f0; font-size: 0.9rem; color: var(--text-muted);">
                        <p>📍 <?php echo htmlspecialchars($product['address'] ?? 'N/A'); ?></p>
                    </div>
                </div>
            </div>

            <!-- Right Column: Bidding Interface -->
            <div style="position: sticky; top: 100px; height: fit-content;">
                <div class="card" style="box-shadow: var(--shadow-lg); border: 1px solid #e2e8f0; padding: 2rem;">

                    <div style="margin-bottom: 1.5rem;">
                        <span class="badge badge-primary" style="margin-bottom: 0.5rem; display: inline-block;"><?php echo htmlspecialchars($product['category'] ?? 'N/A'); ?></span>
                        <h1 style="font-size: 1.75rem; line-height: 1.2; margin-bottom: 0.5rem;"><?php echo htmlspecialchars($product['product_name']); ?></h1>
                        <p style="color: var(--text-muted);">Listed by <?php echo htmlspecialchars($product['company_name'] ?? 'N/A'); ?></p>
                    </div>

                    <div style="background: #f8fafc; border-radius: 0.75rem; padding: 1.5rem; margin-bottom: 2rem;">
                        <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 0.5rem;">Current Highest Offer</p>
                        <div style="display: flex; align-items: baseline; gap: 0.5rem; margin-bottom: 1rem;">
                            <span style="font-size: 2.25rem; font-weight: 800; color: var(--primary); letter-spacing: -1px;">
                                <?php echo format_currency($highest_bid ?: $product['base_price']); ?>
                            </span>
                        </div>
                        <div style="display: flex; gap: 2rem; border-top: 1px solid #e2e8f0; padding-top: 1rem;">
                            <div>
                                <p style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase;">Total Bids</p>
                                <p style="font-weight: 600;"><?php echo $bid_count; ?></p>
                            </div>
                            <div>
                                <p style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase;">Duration</p>
                                <p style="font-weight: 600;"><?php echo get_time_remaining($product['bid_end']); ?></p>
                            </div>
                        </div>
                    </div>

                    <?php
                    $now = date('Y-m-d H:i:s');
                    $is_upcoming = ($now < $product['bid_start']);
                    $is_ended = ($now > $product['bid_end']);
                    ?>

                    <?php if ($is_active && $product['status'] === 'open'): ?>
                        <?php if (!$current_user_id): ?>
                            <div style="text-align: center;">
                                <a href="pages/login.php?redirect=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>" class="btn btn-primary w-full" style="padding: 1rem; font-size: 1.1rem; margin-bottom: 1rem;">Place a Bid</a>
                                <p style="font-size: 0.9rem; color: var(--text-muted);">
                                    <span style="display: block; margin-bottom: 0.5rem;">Don't have an account?</span>
                                    <a href="pages/register_client.php" style="color: var(--primary); font-weight: 600;">Register Now</a>
                                </p>
                            </div>
                        <?php elseif ($current_role === 'client'): ?>

                            <?php if ($my_bid): ?>
                                <div style="background: #ecfdf5; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem; border: 1px solid #d1fae5;">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                        <p style="color: #065f46; font-weight: 700; font-size: 0.9rem;">Your Bid</p>
                                        <span class="badge badge-<?php echo $my_bid['bid_status'] === 'approved' ? 'success' : ($my_bid['bid_status'] === 'rejected' ? 'danger' : 'warning'); ?>">
                                            <?php echo ucfirst($my_bid['bid_status']); ?>
                                        </span>
                                    </div>
                                    <p style="font-size: 1.25rem; font-weight: 700; color: #047857;"><?php echo format_currency($my_bid['bid_amount']); ?></p>
                                </div>
                                <a href="client/place_bid.php?id=<?php echo $product_id; ?>" class="btn btn-secondary w-full" style="padding: 1rem;">Update Bid</a>
                            <?php else: ?>
                                <a href="client/place_bid.php?id=<?php echo $product_id; ?>" class="btn btn-primary w-full" style="padding: 1rem; font-size: 1.1rem; box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2);">
                                    Place Bid Now
                                </a>
                                <p style="text-align: center; margin-top: 1rem; font-size: 0.8rem; color: var(--text-muted);">
                                    By placing a bid, you agree to our Terms of Service.
                                </p>
                            <?php endif; ?>

                        <?php else: ?>
                            <div class="alert alert-warning">
                                Registered as <strong><?php echo ucfirst($current_role); ?></strong>. Login as a Client to place bids.
                            </div>
                        <?php endif; ?>
                    <?php elseif ($is_upcoming && $product['status'] === 'open'): ?>
                        <?php
                        $is_reminding = false;
                        if ($current_user_id) {
                            $check_rem = fetch_one("SELECT reminder_id FROM product_reminders WHERE user_id = ? AND product_id = ?", [$current_user_id, $product_id]);
                            if ($check_rem) $is_reminding = true;
                        }
                        ?>
                        <div style="text-align: center; padding: 2rem; background: #fff7ed; border-radius: 0.5rem; border: 1px solid #ffedd5;">
                            <h3 style="color: #9a3412; margin-bottom: 0.5rem;">Bidding Starts Soon</h3>
                            <p style="color: #c2410c; font-weight: 500; margin-bottom: 1.5rem;">
                                Starts on <?php echo format_date($product['bid_start']); ?>
                            </p>

                            <?php if ($current_role === 'client'): ?>
                                <button onclick="toggleReminder(<?php echo $product_id; ?>, this)" class="btn w-full"
                                    style="padding: 0.75rem; 
                                           background: <?php echo $is_reminding ? '#fef3c7' : 'white'; ?>; 
                                           color: <?php echo $is_reminding ? '#d97706' : '#c2410c'; ?>; 
                                           border: 1px solid <?php echo $is_reminding ? '#fcd34d' : '#fdba74'; ?>;">
                                    <?php if ($is_reminding): ?>
                                        <img src="<?php echo APP_URL; ?>/assets/images/Notion-Resources/Ecommerce-Club/Regular/png/ec-notification.png" alt="Set" style="width: 20px; height: 20px; margin-right: 8px; vertical-align: middle;"> Reminder Set
                                    <?php else: ?>
                                        <img src="<?php echo APP_URL; ?>/assets/images/Notion-Resources/Notion-Icons/Regular/png/ni-bell.png" alt="Notify" style="width: 20px; height: 20px; margin-right: 8px; vertical-align: middle;"> Notify Me
                                    <?php endif; ?>
                                </button>
                            <?php elseif (!$current_user_id): ?>
                                <a href="pages/login.php" class="btn btn-secondary w-full">Login to set reminder</a>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <button class="btn btn-secondary w-full" disabled style="opacity: 0.7; cursor: not-allowed; padding: 1rem;">Bidding Closed</button>
                        <p style="text-align: center; margin-top: 1rem; color: #ef4444; font-weight: 500;">
                            <?php echo ($product['status'] !== 'open') ? 'Status: ' . ucfirst($product['status']) : 'This auction has ended.'; ?>
                        </p>
                    <?php endif; ?>

                </div>

                <script>
                    function toggleReminder(productId, btn) {
                        fetch('<?php echo APP_URL; ?>/client/toggle_reminder.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    product_id: productId
                                })
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    if (data.status === 'added') {
                                        btn.innerHTML = '<img src="<?php echo APP_URL; ?>/assets/images/Notion-Resources/Ecommerce-Club/Regular/png/ec-notification.png" alt="Set" style="width: 20px; height: 20px; margin-right: 8px; vertical-align: middle;"> Reminder Set';
                                        btn.style.background = '#fef3c7';
                                        btn.style.color = '#d97706';
                                        btn.style.borderColor = '#fcd34d';
                                    } else {
                                        btn.innerHTML = '<img src="<?php echo APP_URL; ?>/assets/images/Notion-Resources/Notion-Icons/Regular/png/ni-bell.png" alt="Notify" style="width: 20px; height: 20px; margin-right: 8px; vertical-align: middle;"> Notify Me';
                                        btn.style.background = 'white';
                                        btn.style.color = '#c2410c';
                                        btn.style.borderColor = '#fdba74';
                                    }
                                } else {
                                    alert(data.message || 'Error occurred');
                                }
                            })
                            .catch(err => console.error(err));
                    }
                </script>

                <div style="margin-top: 2rem; text-align: center;">
                    <p style="color: var(--text-muted); font-size: 0.9rem;">Need help?</p>
                    <a href="<?php echo APP_URL; ?>/pages/contact.php" style="color: var(--primary); font-weight: 500; font-size: 0.9rem;">Contact Support</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>