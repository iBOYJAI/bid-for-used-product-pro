<?php
$page_title = 'Admin Dashboard';
require_once __DIR__ . '/../includes/header.php';
require_login('admin');

// Fetch Stats
$total_users = fetch_one("SELECT COUNT(*) as count FROM users")['count'];
$total_companies = fetch_one("SELECT COUNT(*) as count FROM companies")['count'];
$pending_companies = fetch_one("SELECT COUNT(*) as count FROM companies WHERE verified_status = 'pending'")['count'];
$total_products = fetch_one("SELECT COUNT(*) as count FROM products")['count'];
$total_bids = fetch_one("SELECT COUNT(*) as count FROM bids")['count'];

?>

<div class="container" style="padding: 2rem 1.5rem;">
    <div class="flex-between" style="margin-bottom: 2rem;">
        <h1 style="font-size: 1.75rem;">Admin Overview</h1>
        <p style="color: var(--text-muted);"><?php echo date('l, d F Y'); ?></p>
    </div>

    <!-- Stats Grid -->
    <div class="grid-4" style="margin-bottom: 2rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem;">
        <!-- Total Users -->
        <a href="manage_users.php" class="card" style="text-decoration: none; color: inherit; display: block; transition: transform 0.2s;">
            <p style="color: var(--text-muted); font-size: 0.875rem;">Total Users</p>
            <p style="font-size: 2rem; font-weight: 700; color: var(--primary);"><?php echo $total_users; ?></p>
        </a>

        <!-- Companies -->
        <a href="verify_companies.php" class="card" style="text-decoration: none; color: inherit; display: block; transition: transform 0.2s;">
            <div class="flex-between">
                <p style="color: var(--text-muted); font-size: 0.875rem;">Companies</p>
                <?php if ($pending_companies > 0): ?>
                    <span class="badge badge-warning" style="background: #fffbeb; color: #b45309; padding: 2px 8px; border-radius: 999px; font-size: 0.75rem;"><?php echo $pending_companies; ?> Pending</span>
                <?php endif; ?>
            </div>
            <p style="font-size: 2rem; font-weight: 700; color: var(--secondary);"><?php echo $total_companies; ?></p>
        </a>

        <!-- Total Products -->
        <a href="view_all_products.php" class="card" style="text-decoration: none; color: inherit; display: block; transition: transform 0.2s;">
            <p style="color: var(--text-muted); font-size: 0.875rem;">Total Products</p>
            <p style="font-size: 2rem; font-weight: 700; color: var(--accent);"><?php echo $total_products; ?></p>
        </a>

        <!-- Total Bids -->
        <a href="view_all_bids.php" class="card" style="text-decoration: none; color: inherit; display: block; transition: transform 0.2s;">
            <p style="color: var(--text-muted); font-size: 0.875rem;">Total Bids</p>
            <p style="font-size: 2rem; font-weight: 700; color: #10b981;"><?php echo $total_bids; ?></p>
        </a>
    </div>

    <!-- Analytics Charts -->
    <div class="grid-2" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        <div class="card">
            <h3 style="margin-bottom: 1rem; font-size: 1rem; color: var(--text-muted);">Bid Activity (7 Days)</h3>
            <canvas id="bidsChart" style="width: 100%; height: 250px;"></canvas>
        </div>
        <div class="card">
            <h3 style="margin-bottom: 1rem; font-size: 1rem; color: var(--text-muted);">User Distribution</h3>
            <canvas id="usersChart" style="width: 100%; height: 250px;"></canvas>
        </div>
    </div>

    <script src="../assets/js/chart.js"></script>
    <script>
        const ctxBids = document.getElementById('bidsChart').getContext('2d');
        const bidsChart = new Chart(ctxBids, {
            type: 'line',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'New Bids',
                    data: [12, 19, 3, 5, 2, 3, <?php echo $total_bids > 0 ? $total_bids : 10; ?>],
                    borderColor: '#FEB05D',
                    backgroundColor: 'rgba(254, 176, 93, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        const ctxUsers = document.getElementById('usersChart').getContext('2d');
        const usersChart = new Chart(ctxUsers, {
            type: 'doughnut',
            data: {
                labels: ['Buyers', 'Sellers'],
                datasets: [{
                    data: [<?php echo $total_users; ?>, <?php echo $total_companies; ?>],
                    backgroundColor: ['#5A7ACD', '#10b981']
                }]
            },
            options: {
                responsive: true
            }
        });
    </script>


    <h2 style="font-size: 1.25rem; margin-bottom: 1rem;">Admin Controls</h2>

    <div class="grid-3" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
        <a href="manage_users.php" class="card" style="text-decoration: none; display: flex; align-items: center; gap: 1rem; transition: transform 0.2s;">
            <div style="background: #e0f2fe; padding: 1rem; border-radius: 0.5rem; color: var(--primary);">
                <span style="font-size: 1.5rem;">👥</span>
            </div>
            <div>
                <h3 style="font-size: 1rem; margin-bottom: 0.25rem; color: var(--text-main);">Manage Users</h3>
                <p style="color: var(--text-muted); font-size: 0.875rem;">View and edit users</p>
            </div>
        </a>

        <a href="reports.php" class="card" style="text-decoration: none; display: flex; align-items: center; gap: 1rem; transition: transform 0.2s;">
            <div style="background: #ede9fe; padding: 1rem; border-radius: 0.5rem; color: #8b5cf6;">
                <span style="font-size: 1.5rem;">📊</span>
            </div>
            <div>
                <h3 style="font-size: 1rem; margin-bottom: 0.25rem; color: var(--text-main);">Reports & Logs</h3>
                <p style="color: var(--text-muted); font-size: 0.875rem;">Download analytics</p>
            </div>
        </a>

        <a href="settings.php" class="card" style="text-decoration: none; display: flex; align-items: center; gap: 1rem; transition: transform 0.2s;">
            <div style="background: #fef3c7; padding: 1rem; border-radius: 0.5rem; color: #d97706;">
                <span style="font-size: 1.5rem;">⚙️</span>
            </div>
            <div>
                <h3 style="font-size: 1rem; margin-bottom: 0.25rem; color: var(--text-main);">Web Settings</h3>
                <p style="color: var(--text-muted); font-size: 0.875rem;">Configure platform</p>
            </div>
        </a>

        <a href="view_all_products.php" class="card" style="text-decoration: none; display: flex; align-items: center; gap: 1rem; transition: transform 0.2s;">
            <div style="background: #dcfce7; padding: 1rem; border-radius: 0.5rem; color: #166534;">
                <span style="font-size: 1.5rem;">📦</span>
            </div>
            <div>
                <h3 style="font-size: 1rem; margin-bottom: 0.25rem; color: var(--text-main);">All Products</h3>
                <p style="color: var(--text-muted); font-size: 0.875rem;">Monitor listings</p>
            </div>
        </a>

        <!-- System Manager Hidden for Security - Access directly via /admin/system_manager.php -->
        <!--
        <a href="system_manager.php" class="card" style="text-decoration: none; display: flex; align-items: center; gap: 1rem; transition: transform 0.2s; background: linear-gradient(135deg, #fee2e2 0%, #fef2f2 100%); border: 2px solid #dc2626;">
            <div style="background: #dc2626; padding: 1rem; border-radius: 0.5rem; color: white;">
                <span style="font-size: 1.5rem;">🔧</span>
            </div>
            <div>
                <h3 style="font-size: 1rem; margin-bottom: 0.25rem; color: #dc2626; font-weight: 700;">System Manager</h3>
                <p style="color: #dc2626; font-size: 0.875rem; font-weight: 500;">⚠️ Advanced Controls</p>
            </div>
        </a>
        -->
    </div>

    <!-- Decorative Footer Image -->
    <div style="margin-top: 3rem; text-align: center; opacity: 0.8;">
        <img src="<?php echo APP_URL; ?>/assets/images/Notion-Resources/Ecommerce-Club/Regular/png/ec-analyzing-market-price.png" alt="" style="height: 200px;">
        <p style="color: var(--text-muted); margin-top: 1rem;">System Overview Panel</p>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>