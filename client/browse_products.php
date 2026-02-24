<?php
$page_title = 'Browse Products';
require_once __DIR__ . '/../includes/header.php';
require_login('client');

// Get filter parameters
$category = isset($_GET['category']) ? $_GET['category'] : '';
$search = isset($_GET['search']) ? sanitize_input($_GET['search']) : '';
$user_id = get_user_id();

// Build query
$sql = "SELECT p.*, c.company_name,
        (SELECT COUNT(*) FROM product_reminders pr WHERE pr.product_id = p.product_id AND pr.user_id = ?) as has_reminder
        FROM products p 
        INNER JOIN companies c ON p.company_id = c.company_id 
        WHERE p.status = 'open' AND p.bid_end >= NOW()";

$params = [$user_id];

if ($category) {
    $sql .= " AND p.category = ?";
    $params[] = $category;
}

if ($search) {
    $sql .= " AND (p.product_name LIKE ? OR p.model LIKE ?)";
    $params[] = "%{$search}%";
    $params[] = "%{$search}%";
}

$sql .= " ORDER BY p.created_at DESC";
$products = fetch_all($sql, $params);
?>

<div style="background: #F5F2F2; padding: 3rem 0; border-bottom: 1px solid #e2e8f0;">
    <div class="container">
        <div class="flex-between">
            <div>
                <h1 style="font-size: 2rem; margin-bottom: 0.5rem; color: #2B2A2A;">Browse Auctions</h1>
                <p style="color: var(--text-muted); font-size: 1.1rem; margin: 0;">Find active and upcoming auctions.</p>
            </div>
            <a href="dashboard.php" class="btn btn-secondary">&larr; Dashboard</a>
        </div>
    </div>
</div>

<div class="container" style="padding: 3rem 1.5rem; margin-top: -2rem;">
    <!-- Filter Bar -->
    <div class="card" style="margin-bottom: 3rem; border: none; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);">
        <form method="GET" style="display: flex; gap: 1rem; flex-wrap: wrap; padding: 0.5rem;">
            <div style="flex: 2; min-width: 250px;">
                <input type="text" name="search" class="form-control" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>" style="height: 50px;">
            </div>
            <div style="flex: 1; min-width: 200px;">
                <select name="category" class="form-control" style="height: 50px;">
                    <option value="">All Categories</option>
                    <option value="2-wheeler" <?php echo $category === '2-wheeler' ? 'selected' : ''; ?>>2-Wheeler</option>
                    <option value="4-wheeler" <?php echo $category === '4-wheeler' ? 'selected' : ''; ?>>4-Wheeler</option>
                    <option value="machinery" <?php echo $category === 'machinery' ? 'selected' : ''; ?>>Machinery</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="height: 50px; padding: 0 2rem;">Search</button>
            <?php if ($search || $category): ?>
                <a href="browse_products.php" class="btn btn-secondary" style="height: 50px; display: flex; align-items: center;">Clear</a>
            <?php endif; ?>
        </form>
    </div>

    <?php if (empty($products)): ?>
        <div class="text-center" style="padding: 5rem 1rem; background: white; border-radius: 1rem; border: 2px dashed #e2e8f0;">
            <div style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;">🔍</div>
            <h3 style="margin-bottom: 0.5rem; color: #1e293b;">No products found</h3>
            <p style="color: var(--text-muted);">Try adjusting your filters.</p>
        </div>
    <?php else: ?>
        <div class="grid-3">
            <?php foreach ($products as $product): ?>
                <?php
                $now_ts = time();
                $start_ts = strtotime($product['bid_start']);
                $end_ts = strtotime($product['bid_end']);

                $is_upcoming = ($now_ts < $start_ts);
                $is_active = ($now_ts >= $start_ts && $now_ts <= $end_ts);

                $image_url = $product['product_image']
                    ? APP_URL . '/uploads/products/' . $product['product_image']
                    : null;
                ?>
                <div class="card" style="padding: 0; overflow: hidden; display: flex; flex-direction: column; height: 100%; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
                    <!-- Image -->
                    <div style="position: relative; height: 220px; overflow: hidden; background: #f1f5f9;">
                        <?php if ($image_url): ?>
                            <img src="<?php echo $image_url; ?>" style="width: 100%; height: 100%; object-fit: cover;">
                        <?php else: ?>
                            <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: var(--text-muted); font-size: 0.85rem;">No image</div>
                        <?php endif; ?>

                        <!-- Status Badge -->
                        <div style="position: absolute; top: 1rem; right: 1rem;">
                            <?php if ($is_active): ?>
                                <span class="badge" style="background: rgba(22, 163, 74, 0.9); color: white; backdrop-filter: blur(4px);">Live</span>
                            <?php elseif ($is_upcoming): ?>
                                <span class="badge" style="background: rgba(234, 88, 12, 0.9); color: white; backdrop-filter: blur(4px);">Upcoming</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Content -->
                    <div style="padding: 1.5rem; display: flex; flex-direction: column; flex: 1;">
                        <span class="badge badge-secondary" style="align-self: start; margin-bottom: 0.75rem; font-size: 0.7rem;"><?php echo htmlspecialchars($product['category']); ?></span>

                        <h3 style="font-size: 1.25rem; margin-bottom: 0.5rem; line-height: 1.3;">
                            <a href="../product_details.php?id=<?php echo $product['product_id']; ?>" style="text-decoration: none; color: inherit;">
                                <?php echo htmlspecialchars($product['product_name']); ?>
                            </a>
                        </h3>
                        <p style="font-size: 0.875rem; color: var(--text-muted); margin-bottom: 1.5rem;">
                            By <span style="font-weight: 500;"><?php echo htmlspecialchars($product['company_name']); ?></span>
                        </p>

                        <div style="margin-top: auto; padding-top: 1rem; border-top: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between;">
                            <div>
                                <p style="font-size: 0.7rem; color: #94a3b8; text-transform: uppercase; font-weight: 600;">Starting Bid</p>
                                <p style="font-weight: 700; font-size: 1.25rem; color: var(--primary);">
                                    <?php if ($is_upcoming): ?>
                                        <span style="font-size: 1rem; color: var(--text-muted); font-style: italic;">Revealed Soon</span>
                                    <?php else: ?>
                                        <?php echo format_currency($product['base_price']); ?>
                                    <?php endif; ?>
                                </p>
                            </div>

                            <?php if ($is_active): ?>
                                <a href="place_bid.php?id=<?php echo $product['product_id']; ?>" class="btn btn-sm btn-primary">Bid Now</a>
                            <?php elseif ($is_upcoming): ?>
                                <button onclick="toggleReminder(<?php echo $product['product_id']; ?>, this)"
                                    class="btn btn-sm"
                                    style="border: 1px solid <?php echo $product['has_reminder'] ? '#fcd34d' : '#e2e8f0'; ?>;
                                           background: <?php echo $product['has_reminder'] ? '#fef3c7' : 'white'; ?>;
                                           color: <?php echo $product['has_reminder'] ? '#d97706' : '#64748b'; ?>;">
                                    <?php if ($product['has_reminder']): ?>
                                        <img src="<?php echo APP_URL; ?>/assets/images/Notion-Resources/Ecommerce-Club/Regular/png/ec-notification.png" alt="Set" style="width: 16px; height: 16px; margin-right: 4px;"> Set
                                    <?php else: ?>
                                        <img src="<?php echo APP_URL; ?>/assets/images/Notion-Resources/Notion-Icons/Regular/png/ni-bell.png" alt="Notify" style="width: 16px; height: 16px; margin-right: 4px;"> Notify
                                    <?php endif; ?>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script>
    function toggleReminder(productId, btn) {
        fetch('toggle_reminder.php', {
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
                        btn.innerHTML = '<img src="<?php echo APP_URL; ?>/assets/images/Notion-Resources/Ecommerce-Club/Regular/png/ec-notification.png" alt="Set" style="width: 16px; height: 16px; margin-right: 4px;"> Set';
                        btn.style.background = '#fef3c7';
                        btn.style.color = '#d97706';
                        btn.style.borderColor = '#fcd34d';
                    } else {
                        btn.innerHTML = '<img src="<?php echo APP_URL; ?>/assets/images/Notion-Resources/Notion-Icons/Regular/png/ni-bell.png" alt="Notify" style="width: 16px; height: 16px; margin-right: 4px;"> Notify';
                        btn.style.background = 'white';
                        btn.style.color = '#64748b';
                        btn.style.borderColor = '#e2e8f0';
                    }
                } else {
                    alert(data.message || 'Error occurred');
                }
            })
            .catch(err => console.error(err));
    }
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>