<?php
ob_start(); // Prevent immediate output
require_once __DIR__ . '/config/config.php';

// Maintenance Mode Check
// Check if table exists first to avoid fatal errors during setup
try {
    $settings_check = fetch_one("SHOW TABLES LIKE 'site_settings'");
    if ($settings_check) {
        $m_mode = fetch_one("SELECT setting_value FROM site_settings WHERE setting_key = 'maintenance_mode'");

        // If maintenance mode is ON ('1')
        if ($m_mode && $m_mode['setting_value'] === '1') {
            // Allow Admin to bypass
            // Need to start session to check role
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $role = $_SESSION['role'] ?? '';

            // If not admin, and not already on the maintenance page, redirect
            if ($role !== 'admin' && basename($_SERVER['PHP_SELF']) !== 'maintenance.php' && basename($_SERVER['PHP_SELF']) !== 'login.php') {
                header("Location: " . APP_URL . "/maintenance.php");
                exit;
            }
        }
    }
} catch (Exception $e) {
    // Fail silently if DB not ready
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/modern.css">
    <link rel="stylesheet" href="assets/css/modern.css">
</head>

<body style="display: flex; align-items: center; justify-content: center; min-height: 100vh; background: radial-gradient(circle at center, #fffbeb 0%, #fff 100%); text-align: center; padding: 2rem;">

    <div class="card" style="max-width: 600px; padding: 3rem; border-top: 5px solid #f59e0b;">
        <img src="<?php echo APP_URL; ?>/assets/images/Notion-Resources/Office-Club/Regular/png/oc-puzzle.png" alt="Maintenance" style="max-width: 250px; margin-bottom: 2rem;">

        <h1 style="font-size: 2.5rem; margin-bottom: 1rem; color: #b45309;">Under Maintenance</h1>

        <p style="font-size: 1.1rem; color: var(--text-muted); margin-bottom: 2rem; line-height: 1.8;">
            We are currently performing scheduled maintenance to improve our platform. <br>
            Please check back soon.
        </p>

        <div style="background: #fef3c7; color: #92400e; padding: 1rem; border-radius: 0.5rem; display: inline-block;">
            Estimated Return: <strong>Very Soon</strong>
        </div>

        <div style="margin-top: 3rem;">
            <a href="pages/login.php" style="color: var(--text-muted); font-size: 0.9rem;">Admin Login</a>
        </div>
    </div>

</body>

</html>