<?php

/**
 * Header Include File
 * Modern layout with guest access support
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/functions.php';

// Only require validation if needed, or check if file exists
if (file_exists(__DIR__ . '/../includes/validation.php')) {
    require_once __DIR__ . '/../includes/validation.php';
}

$is_logged_in = is_logged_in();
$user_name = $is_logged_in ? get_user_name() : 'Guest';
$user_role = $is_logged_in ? get_user_role() : null;

// Maintenance Mode Check
// Maintenance Mode Check
try {
    $m_mode_setting = fetch_one("SELECT setting_value FROM site_settings WHERE setting_key = 'maintenance_mode'");
    if ($m_mode_setting && $m_mode_setting['setting_value'] === '1') {
        // Allow access to login and maintenance pages to prevent redirect loops
        $current_page = basename($_SERVER['PHP_SELF']);
        $allowed_pages = ['maintenance.php', 'login.php', 'register_client.php', 'register_company.php', 'admin_login.php']; // Add auth pages if needed

        // If not admin and not on allowed page, redirect
        if ($user_role !== 'admin' && !in_array($current_page, $allowed_pages)) {
            header("Location: " . APP_URL . "/maintenance.php");
            exit;
        }
    }
} catch (Exception $e) {
    // Fail safe
}

// Fetch dynamic site name
$site_setting_name = null;
try {
    $site_setting_name = fetch_one("SELECT setting_value FROM site_settings WHERE setting_key = 'site_name'");
} catch (Exception $e) {
}
$display_app_name = ($site_setting_name && !empty($site_setting_name['setting_value'])) ? $site_setting_name['setting_value'] : APP_NAME;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?><?php echo htmlspecialchars($display_app_name); ?></title>
    <!-- Use the new modern CSS -->
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/assets/css/modern.css">
</head>

<body>
    <?php if (!isset($_SESSION['welcome_shown'])): ?>
        <?php $_SESSION['welcome_shown'] = true; ?>
        <!-- Welcome Loader -->
        <div id="page-loader" style="position: fixed; inset: 0; background: #FFF7ED; z-index: 9999; display: flex; align-items: center; justify-content: center; transition: opacity 0.6s ease; flex-direction: column; gap: 1rem;">
            <div style="width: 60px; height: 60px; border: 5px solid #fed7aa; border-top-color: #FEB05D; border-radius: 50%; animation: spin 1s linear infinite;"></div>
            <div style="text-align: center;">
                <p style="color: #5A7ACD; font-weight: 600; letter-spacing: 2px; text-transform: uppercase; font-size: 0.9rem; margin-bottom: 0.5rem;">Welcome to</p>
                <h2 style="color: #2B2A2A; font-size: 2rem; font-weight: 400; margin: 0; text-transform: uppercase; letter-spacing: -1px;">
                    <?php echo htmlspecialchars($display_app_name); ?>
                </h2>
            </div>
        </div>
        <script>
            window.addEventListener('load', function() {
                // Keep loader visible for at least 1.5s for the 'Welcome' experience
                setTimeout(function() {
                    const loader = document.getElementById('page-loader');
                    if (loader) {
                        loader.style.opacity = '0';
                        loader.style.pointerEvents = 'none';
                        setTimeout(() => loader.style.display = 'none', 600);
                    }
                }, 1200);
            });
        </script>
        <style>
            @keyframes spin {
                to {
                    transform: rotate(360deg);
                }
            }
        </style>
    <?php endif; ?>

    <nav class="navbar">
        <div class="container navbar-container">
            <a href="<?php echo APP_URL; ?>/" class="navbar-brand" style="text-transform: uppercase; font-size: 1.25rem;">
                <?php echo htmlspecialchars($display_app_name); ?>
            </a>

            <ul class="navbar-menu">
                <li><a href="<?php echo APP_URL; ?>/" class="nav-link">Home</a></li>
                <li><a href="<?php echo APP_URL; ?>/products.php" class="nav-link">Products</a></li>

                <?php if ($is_logged_in): ?>
                    <li>
                        <?php
                        include_once __DIR__ . '/notifications.php';
                        $unread_count = get_unread_count(get_user_id());
                        ?>
                        <!-- Notification Click Link -->
                        <a href="<?php echo APP_URL; ?>/notifications.php" class="nav-link" style="position: relative; display: flex; align-items: center;" title="View Notifications">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                                <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                            </svg>
                            <?php if ($unread_count > 0): ?>
                                <span style="position: absolute; top: -6px; right: -6px; background: #ef4444; color: white; border-radius: 50%; width: 18px; height: 18px; font-size: 11px; display: flex; align-items: center; justify-content: center; font-weight: bold; border: 2px solid white;"><?php echo $unread_count; ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <li>
                        <?php
                        /* Combined Avatar Logic */
                        $u_id = get_user_id();
                        $user_data_av = fetch_one("SELECT avatar FROM users WHERE user_id = ?", [$u_id]);

                        if ($user_data_av && !empty($user_data_av['avatar'])) {
                            $my_avatar = APP_URL . '/assets/images/Avatar/' . $user_data_av['avatar'];
                        } else {
                            $avatars = ['boy-1.png', 'boy-2.png', 'boy-3.png', 'boy-4.png', 'boy-5.png', 'boy-6.png', 'boy-7.png', 'girl-1.png', 'girl-2.png', 'girl-3.png', 'girl-4.png'];
                            $idx = ($u_id) % count($avatars);
                            $my_avatar = APP_URL . '/assets/images/Avatar/' . $avatars[$idx];
                        }
                        ?>
                        <!-- User Profile Dropdown / Link -->
                        <a href="<?php echo APP_URL; ?>/profile.php" class="nav-link" style="display: flex; align-items: center; gap: 10px; padding: 4px 8px; border-radius: 50px; transition: background 0.2s;">
                            <div style="width: 32px; height: 32px; border-radius: 50%; overflow: hidden; border: 2px solid var(--primary);">
                                <img src="<?php echo $my_avatar; ?>" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <span style="font-weight: 600; font-size: 0.9rem; display: none; @media(min-width: 768px){display:block;}">
                                <?php echo htmlspecialchars($user_name); ?>
                            </span>
                        </a>
                    </li>
                    <?php if ($user_role === 'admin'): ?>
                        <li><a href="<?php echo APP_URL; ?>/admin/dashboard.php" class="btn btn-sm btn-primary">Dashboard</a></li>
                    <?php elseif ($user_role === 'company'): ?>
                        <li><a href="<?php echo APP_URL; ?>/company/dashboard.php" class="btn btn-sm btn-primary">Dashboard</a></li>
                    <?php else: ?>
                        <li><a href="<?php echo APP_URL; ?>/client/dashboard.php" class="btn btn-sm btn-primary">Dashboard</a></li>
                    <?php endif; ?>

                    <li><a href="<?php echo APP_URL; ?>/auth/logout.php" class="btn btn-sm btn-secondary">Logout</a></li>

                <?php else: ?>
                    <li><a href="<?php echo APP_URL; ?>/pages/login.php" class="nav-link">Login</a></li>
                    <li><a href="<?php echo APP_URL; ?>/pages/register_client.php" class="btn btn-sm btn-primary">Get Started</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
    <main>