<?php
$page_title = 'Browse Products';
require_once __DIR__ . '/includes/header.php';

// Simple search/filter
$category = isset($_GET['category']) ? $_GET['category'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';

$sql = "SELECT p.*, c.company_name 
        FROM products p 
        JOIN companies c ON p.company_id = c.company_id 
        WHERE p.status = 'open'";
$params = [];

if ($category) {
    $sql .= " AND p.category = ?";
    $params[] = $category;
}

if ($search) {
    $sql .= " AND (p.product_name LIKE ? OR p.model LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$sql .= " ORDER BY p.created_at DESC";

$products = fetch_all($sql, $params);
?>

<div style="background: #F5F2F2; padding: 4rem 0; border-bottom: 1px solid #e2e8f0; position: relative; overflow: hidden;">
    <div class="container" style="position: relative; z-index: 2; display: flex; align-items: center; justify-content: space-between;">
        <div style="max-width: 600px;">
            <span class="badge badge-primary" style="background: white; color: var(--primary); margin-bottom: 1rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">Inventory</span>
            <h1 style="font-size: 3rem; margin-bottom: 1rem; color: #2B2A2A; line-height: 1.1;">Find Your Next <br><span style="color: var(--primary);">Investment</span></h1>
            <p style="color: var(--text-muted); font-size: 1.25rem; margin-bottom: 0;">Explore our curated list of verified used assets. Filter by category, price, or status to find exactly what you need.</p>
        </div>
        <div class="hero-image" style="display: none; @media(min-width: 768px){display: block;}">
            <img src="<?php echo APP_URL; ?>/assets/images/Notion-Resources/Office-Club/Regular/png/oc-target.png" alt="Target" style="width: 300px; opacity: 0.9; transform: rotate(-5deg);">
        </div>
    </div>
    <!-- Decorative Circle -->
    <div style="position: absolute; top: -100px; right: -100px; width: 400px; height: 400px; background: rgba(254, 176, 93, 0.1); border-radius: 50%; z-index: 1;"></div>
</div>

<div class="container" style="padding: 3rem 1.5rem; margin-top: -3rem;">
    <!-- Filter Bar -->
    <div class="card" style="margin-bottom: 3rem; border: none; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);">
        <form action="" method="GET" style="display: flex; gap: 1rem; flex-wrap: wrap; padding: 0.5rem;">
            <div style="flex: 2; min-width: 250px;">
                <input type="text" name="search" class="form-control" placeholder="Search by name, model, or keywords..." value="<?php echo htmlspecialchars($search); ?>" style="height: 50px; border: 1px solid #e2e8f0; padding-left: 1.25rem;">
            </div>
            <div style="flex: 1; min-width: 200px;">
                <select name="category" class="form-control" style="height: 50px; border: 1px solid #e2e8f0;">
                    <option value="">All Categories</option>
                    <option value="2-wheeler" <?php echo $category === '2-wheeler' ? 'selected' : ''; ?>>2-Wheeler</option>
                    <option value="4-wheeler" <?php echo $category === '4-wheeler' ? 'selected' : ''; ?>>4-Wheeler</option>
                    <option value="machinery" <?php echo $category === 'machinery' ? 'selected' : ''; ?>>Machinery</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="height: 50px; padding: 0 2rem; font-weight: 600;">Search</button>
            <?php if ($category || $search): ?>
                <a href="products.php" class="btn btn-secondary" style="height: 50px; display: flex; align-items: center;">Clear</a>
            <?php endif; ?>
        </form>
    </div>

    <?php if (empty($products)): ?>
        <div class="text-center" style="padding: 5rem 1rem; background: white; border-radius: 1rem; border: 2px dashed #e2e8f0;">
            <div style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;">🔍</div>
            <h3 style="margin-bottom: 0.5rem; color: #1e293b;">No products found</h3>
            <p style="color: var(--text-muted);">Try adjusting your search criteria or clear the filters.</p>
            <a href="products.php" class="btn btn-sm btn-primary mt-4">Reset Filters</a>
        </div>
    <?php else: ?>
        <div class="grid-3">
            <?php foreach ($products as $product): ?>
                <?php
                $now_ts = time();
                $start_ts = strtotime($product['bid_start']);
                $end_ts = strtotime($product['bid_end']);

                $is_upcoming = ($now_ts < $start_ts);
                $is_ended = ($now_ts > $end_ts);
                $is_active = ($now_ts >= $start_ts && $now_ts <= $end_ts && $product['status'] === 'open');

                $image_url = $product['product_image']
                    ? APP_URL . '/uploads/products/' . $product['product_image']
                    : null;
                ?>
                <div class="card" style="padding: 0; overflow: hidden; display: flex; flex-direction: column; height: 100%; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
                    <!-- Image Wrapper -->
                    <div style="position: relative; height: 220px; overflow: hidden; background: #f1f5f9;">
                        <?php if ($image_url): ?>
                            <img src="<?php echo $image_url; ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s;">
                        <?php else: ?>
                            <img src="<?php echo APP_URL; ?>/assets/images/Notion-Resources/Office-Club/Regular/png/oc-puzzle.png" alt="No Image" style="width: 100%; height: 100%; object-fit: contain; padding: 2rem; background: #f8fafc;">
                        <?php endif; ?>

                        <!-- Status Badge -->
                        <div style="position: absolute; top: 1rem; right: 1rem;">
                            <?php if ($is_active): ?>
                                <span class="badge" style="background: rgba(22, 163, 74, 0.9); color: white; backdrop-filter: blur(4px); box-shadow: 0 2px 4px rgba(0,0,0,0.1);">Live</span>
                            <?php elseif ($is_upcoming && $product['status'] === 'open'): ?>
                                <span class="badge" style="background: rgba(234, 88, 12, 0.9); color: white; backdrop-filter: blur(4px); box-shadow: 0 2px 4px rgba(0,0,0,0.1);">Upcoming</span>
                            <?php else: ?>
                                <span class="badge" style="background: rgba(100, 116, 139, 0.9); color: white; backdrop-filter: blur(4px); box-shadow: 0 2px 4px rgba(0,0,0,0.1);"><?php echo ($product['status'] !== 'open') ? ucfirst($product['status']) : 'Closed'; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Content -->
                    <div style="padding: 1.5rem; display: flex; flex-direction: column; flex: 1;">
                        <span class="badge badge-secondary" style="align-self: start; margin-bottom: 0.75rem; font-size: 0.7rem; letter-spacing: 0.5px;"><?php echo htmlspecialchars($product['category']); ?></span>

                        <h3 style="font-size: 1.25rem; margin-bottom: 0.5rem; line-height: 1.3;">
                            <a href="product_details.php?id=<?php echo $product['product_id']; ?>" style="text-decoration: none; color: inherit;">
                                <?php echo htmlspecialchars($product['product_name']); ?>
                            </a>
                        </h3>

                        <p style="font-size: 0.875rem; color: var(--text-muted); margin-bottom: 1.5rem;">
                            By <span style="color: var(--text-main); font-weight: 500;"><?php echo htmlspecialchars($product['company_name']); ?></span>
                        </p>

                        <div style="margin-top: auto; padding-top: 1rem; border-top: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between;">
                            <div>
                                <p style="font-size: 0.7rem; color: #94a3b8; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px;">Starting Bid</p>
                                <p style="font-weight: 700; font-size: 1.25rem; color: var(--primary);">
                                    <?php echo format_currency($product['base_price']); ?>
                                </p>
                            </div>
                            <?php if ($is_active): ?>
                                <a href="product_details.php?id=<?php echo $product['product_id']; ?>" class="btn btn-sm btn-primary" style="padding: 0.5rem 1rem;">
                                    Bid Now
                                </a>
                            <?php else: ?>
                                <a href="product_details.php?id=<?php echo $product['product_id']; ?>" class="btn btn-sm btn-secondary" style="padding: 0.5rem 1rem;">
                                    View Details
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>