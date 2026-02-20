<?php
$page_title = 'Admin Dashboard';
require_once __DIR__ . '/../includes/header.php';
require_login('admin');

// Get system statistics
$total_users = fetch_one("SELECT COUNT(*) as count FROM users")['count'];
$total_companies = fetch_one("SELECT COUNT(*) as count FROM users WHERE role = 'company'")['count'];
$total_clients = fetch_one("SELECT COUNT(*) as count FROM users WHERE role = 'client'")['count'];
$total_products = fetch_one("SELECT COUNT(*) as count FROM products")['count'];
$open_products = fetch_one("SELECT COUNT(*) as count FROM products WHERE status = 'open'")['count'];
$total_bids = fetch_one("SELECT COUNT(*) as count FROM bids")['count'];
$approved_bids = fetch_one("SELECT COUNT(*) as count FROM bids WHERE bid_status = 'approved'")['count'];
$pending_verifications = fetch_one("SELECT COUNT(*) as count FROM companies WHERE verified_status = 'pending'")['count'];

// Get recent activity
$recent_users = fetch_all("SELECT * FROM users ORDER BY created_at DESC LIMIT 5", []);
$recent_products = fetch_all("SELECT p.*, c.company_name 
                               FROM products p 
                               INNER JOIN companies c ON p.company_id = c.company_id 
                               ORDER BY p.created_at DESC LIMIT 5", []);
?>

<div class="container">
    <h1 style="margin-bottom: 30px;">Admin Dashboard</h1>
    
    <!-- System Statistics -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total Users</div>
            <div class="stat-value"><?php echo $total_users; ?></div>
        </div>
        <div class="stat-card success">
            <div class="stat-label">Companies</div>
            <div class="stat-value"><?php echo $total_companies; ?></div>
        </div>
        <div class="stat-card warning">
            <div class="stat-label">Clients</div>
            <div class="stat-value"><?php echo $total_clients; ?></div>
        </div>
        <div class="stat-card danger">
            <div class="stat-label">Pending Verifications</div>
            <div class="stat-value"><?php echo $pending_verifications; ?></div>
        </div>
    </div>
    
    <div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
        <div class="stat-card">
            <div class="stat-label">Total Products</div>
            <div class="stat-value"><?php echo $total_products; ?></div>
        </div>
        <div class="stat-card success">
            <div class="stat-label">Open Products</div>
            <div class="stat-value"><?php echo $open_products; ?></div>
        </div>
        <div class="stat-card warning">
            <div class="stat-label">Total Bids</div>
            <div class="stat-value"><?php echo $total_bids; ?></div>
        </div>
        <div class="stat-card danger">
            <div class="stat-label">Approved Bids</div>
            <div class="stat-value"><?php echo $approved_bids; ?></div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="card">
        <div class="card-header">
            <h2>Quick Actions</h2>
        </div>
        <div class="card-body" style="display: flex; gap: 15px; flex-wrap: wrap;">
            <a href="<?php echo APP_URL; ?>/admin/manage_users.php" class="btn btn-primary">Manage Users</a>
            <a href="<?php echo APP_URL; ?>/admin/view_all_products.php" class="btn btn-secondary">View All Products</a>
            <a href="<?php echo APP_URL; ?>/admin/view_all_bids.php" class="btn btn-warning">View All Bids</a>
            <a href="<?php echo APP_URL; ?>/admin/verify_companies.php" class="btn btn-danger">Verify Companies</a>
        </div>
    </div>
    
    <!-- Recent Users -->
    <div class="card">
        <div class="card-header">
            <h2>Recent User Registrations</h2>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Registered At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_users as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['name']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <span class="badge badge-<?php echo $user['role'] === 'admin' ? 'danger' : ($user['role'] === 'company' ? 'warning' : 'info'); ?>">
                                    <?php echo $user['role']; ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-<?php echo $user['status'] === 'active' ? 'success' : 'danger'; ?>">
                                    <?php echo $user['status']; ?>
                                </span>
                            </td>
                            <td><?php echo format_date($user['created_at']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Recent Products -->
    <div class="card">
        <div class="card-header">
            <h2>Recent Products Posted</h2>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Company</th>
                        <th>Category</th>
                        <th>Base Price</th>
                        <th>Status</th>
                        <th>Posted At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_products as $product): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($product['company_name']); ?></td>
                            <td><?php echo htmlspecialchars($product['category']); ?></td>
                            <td><?php echo format_currency($product['base_price']); ?></td>
                            <td>
                                <span class="badge badge-<?php echo $product['status'] === 'open' ? 'success' : 'secondary'; ?>">
                                    <?php echo $product['status']; ?>
                                </span>
                            </td>
                            <td><?php echo format_date($product['created_at']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
