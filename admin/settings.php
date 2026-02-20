<?php
$page_title = 'Site Settings';
require_once __DIR__ . '/../includes/header.php';
require_login('admin');

// Ensure table exists (Self-healing)
try {
    execute_query("CREATE TABLE IF NOT EXISTS site_settings (
        setting_key VARCHAR(50) PRIMARY KEY,
        setting_value TEXT,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )");
} catch (Exception $e) {
    // Table might already exist or permission issue
}

// Handle Form Submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $settings = [
        'site_name' => $_POST['site_name'] ?? APP_NAME,
        'maintenance_mode' => isset($_POST['maintenance_mode']) ? '1' : '0',
        'admin_email' => $_POST['admin_email'] ?? '',
        'welcome_message' => $_POST['welcome_message'] ?? ''
    ];

    foreach ($settings as $key => $value) {
        execute_query("INSERT INTO site_settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?", [$key, $value, $value]);
    }
    $message = "Settings updated successfully!";
}

// Fetch Current Settings
$current_settings = [];
$rows = fetch_all("SELECT * FROM site_settings");
foreach ($rows as $row) {
    $current_settings[$row['setting_key']] = $row['setting_value'];
}
?>

<div class="container" style="padding: 2rem 1.5rem;">
    <div class="flex-between" style="margin-bottom: 2rem;">
        <h1 style="font-size: 1.75rem;">Website Settings</h1>
        <a href="dashboard.php" class="btn btn-secondary">&larr; Back to Dashboard</a>
    </div>

    <?php if ($message): ?>
        <div class="alert alert-success" style="margin-bottom: 1.5rem; background: #dcfce7; color: #166534; padding: 1rem; border-radius: 0.5rem;">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <div class="grid-2" style="display: grid; grid-template-columns: 1fr 300px; gap: 2rem;">

        <!-- Main Settings Form -->
        <div class="card">
            <h2 style="font-size: 1.25rem; margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;">General Configuration</h2>

            <form action="" method="POST">

                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label" style="font-weight: 500;">Site Name</label>
                    <input type="text" name="site_name" class="form-control" value="<?php echo htmlspecialchars($current_settings['site_name'] ?? APP_NAME); ?>" required>
                    <small style="color: var(--text-muted);">Note: This updates the display name in the footer/header.</small>
                </div>

                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label" style="font-weight: 500;">Admin Contact Email</label>
                    <input type="email" name="admin_email" class="form-control" value="<?php echo htmlspecialchars($current_settings['admin_email'] ?? 'admin@example.com'); ?>" required>
                </div>

                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label" style="font-weight: 500;">Welcome Message (Dashboard)</label>
                    <textarea name="welcome_message" class="form-control" rows="2"><?php echo htmlspecialchars($current_settings['welcome_message'] ?? 'Welcome to the Bidding Platform'); ?></textarea>
                </div>

                <div class="form-group" style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" id="maintenance_mode" name="maintenance_mode" <?php echo ($current_settings['maintenance_mode'] ?? '0') === '1' ? 'checked' : ''; ?> style="width: 20px; height: 20px;">
                    <label for="maintenance_mode" style="font-weight: 500;">Enable Maintenance Mode</label>
                </div>

                <button type="submit" class="btn btn-primary">Save Settings</button>
            </form>
        </div>

        <!-- Info / Sidebar -->
        <div>
            <div class="card" style="margin-bottom: 1.5rem; text-align: center;">
                <img src="<?php echo APP_URL; ?>/assets/images/Notion-Resources/Office-Club/Regular/png/oc-puzzle.png" alt="Settings" style="width: 100%; max-width: 150px; margin-bottom: 1rem;">
                <h3 style="font-size: 1.1rem;">System Status</h3>
                <p style="color: #10b981; font-weight: 600; margin-top: 0.5rem;">● System Operational</p>
                <p style="color: var(--text-muted); font-size: 0.875rem; margin-top: 0.5rem;">PHP Version: <?php echo phpversion(); ?></p>
            </div>
        </div>

    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>