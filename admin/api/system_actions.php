<?php

/**
 * System Actions API
 * Handles all system management operations
 */

session_start();
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/database.php';
require_once __DIR__ . '/../../includes/session.php';

// Master password
define('MASTER_PASSWORD', 'iBOY#2026-200cr@iSoulSync');

// Set JSON header
header('Content-Type: application/json');

// Verify admin session
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'error' => 'Unauthorized access']);
    exit;
}

// Verify system manager authentication
if (!isset($_SESSION['system_manager_auth']) || $_SESSION['system_manager_auth'] !== true) {
    echo json_encode(['success' => false, 'error' => 'System manager authentication required']);
    exit;
}

// Get action
$action = $_REQUEST['action'] ?? '';

try {
    switch ($action) {
        case 'get_users':
            handleGetUsers();
            break;

        case 'get_upload_stats':
            handleGetUploadStats();
            break;

        case 'db_wipe':
            handleDbWipe();
            break;

        case 'upload_wipe':
            handleUploadWipe();
            break;

        case 'self_destruct':
            handleSelfDestruct();
            break;

        case 'reset_password':
            handlePasswordReset();
            break;

        case 'generate_seed':
            handleGenerateSeed();
            break;

        default:
            echo json_encode(['success' => false, 'error' => 'Invalid action']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

/**
 * Get all users for password reset dropdown
 */
function handleGetUsers()
{
    $users = fetch_all("SELECT user_id, name, email, role FROM users ORDER BY role, name");
    echo json_encode(['success' => true, 'users' => $users]);
}

/**
 * Get upload folder statistics
 */
function handleGetUploadStats()
{
    $uploadDir = __DIR__ . '/../../uploads/';
    $totalFiles = 0;
    $totalSize = 0;

    function scanDirectory($dir, &$count, &$size)
    {
        if (!is_dir($dir)) return;

        $files = scandir($dir);
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') continue;

            $path = $dir . '/' . $file;
            if (is_file($path)) {
                $count++;
                $size += filesize($path);
            } elseif (is_dir($path)) {
                scanDirectory($path, $count, $size);
            }
        }
    }

    scanDirectory($uploadDir, $totalFiles, $totalSize);

    $sizeFormatted = $totalSize < 1024 ? $totalSize . ' B' : ($totalSize < 1024 * 1024 ? round($totalSize / 1024, 2) . ' KB' :
            round($totalSize / (1024 * 1024), 2) . ' MB');

    echo json_encode([
        'success' => true,
        'stats' => [
            'total_files' => $totalFiles,
            'total_size' => $sizeFormatted,
            'size_bytes' => $totalSize
        ]
    ]);
}

/**
 * Database wipe with multiple modes
 */
function handleDbWipe()
{
    $mode = $_POST['mode'] ?? 'complete';
    $pdo = get_connection();

    $message = "> Mode: $mode\n";

    try {
        $pdo->beginTransaction();

        switch ($mode) {
            case 'selective':
                // Keep one specific user
                $userId = $_POST['user_id'] ?? null;
                $userPassword = $_POST['user_password'] ?? null;

                if (!$userId || !$userPassword) {
                    throw new Exception('User ID and password required for selective mode');
                }

                // Verify user exists and password matches
                $user = fetch_one("SELECT * FROM users WHERE user_id = ?", [$userId]);
                if (!$user || !password_verify($userPassword, $user['password'])) {
                    throw new Exception('Invalid user ID or password');
                }

                $message .= "> Keeping user ID: $userId ({$user['name']})\n";
                $message .= "> Deleting all other data...\n";

                // Delete all users except the specified one
                $pdo->exec("DELETE FROM users WHERE user_id != $userId");

                $message .= "> Database wiped (1 user kept)\n";
                break;

            case 'role-based':
                // Keep one user of each role type
                $message .= "> Keeping one admin, one company, one client\n";
                $message .= "> Deleting all other data...\n";

                // Get one user of each role
                $adminId = fetch_one("SELECT user_id FROM users WHERE role = 'admin' LIMIT 1")['user_id'] ?? 1;
                $companyId = fetch_one("SELECT user_id FROM users WHERE role = 'company' LIMIT 1")['user_id'] ?? 2;
                $clientId = fetch_one("SELECT user_id FROM users WHERE role = 'client' LIMIT 1")['user_id'] ?? 10;

                // Delete all users except these three
                $pdo->exec("DELETE FROM users WHERE user_id NOT IN ($adminId, $companyId, $clientId)");

                // Delete all products and bids
                $pdo->exec("DELETE FROM bids");
                $pdo->exec("DELETE FROM products");
                $pdo->exec("DELETE FROM notifications");
                $pdo->exec("DELETE FROM messages");

                $message .= "> Database wiped (3 users kept)\n";
                break;

            case 'complete':
            default:
                // Complete wipe - only keep default admin
                $message .= "> Complete wipe - keeping only default admin\n";
                $message .= "> Deleting all data...\n";

                // Delete everything except admin user ID 1
                $pdo->exec("DELETE FROM users WHERE user_id != 1");

                $message .= "> Database wiped (admin only)\n";
                break;
        }

        $pdo->commit();

        echo json_encode([
            'success' => true,
            'message' => $message
        ]);
    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
}

/**
 * Wipe upload folder
 */
function handleUploadWipe()
{
    $uploadDir = __DIR__ . '/../../uploads/';
    $message = "> Starting upload folder wipe...\n";

    function deleteDirectory($dir, &$msg)
    {
        if (!is_dir($dir)) return;

        $files = scandir($dir);
        foreach ($files as $file) {
            if ($file === '.' || $file === '..' || $file === '.htaccess') continue;

            $path = $dir . '/' . $file;
            if (is_file($path)) {
                unlink($path);
                $msg .= "> Deleted: $file\n";
            } elseif (is_dir($path)) {
                deleteDirectory($path, $msg);
                rmdir($path);
            }
        }
    }

    deleteDirectory($uploadDir, $message);

    // Recreate directory structure
    $dirs = [
        $uploadDir . 'products',
        $uploadDir . 'identity_proofs'
    ];

    foreach ($dirs as $dir) {
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
            $message .= "> Recreated: " . basename($dir) . "\n";
        }
    }

    $message .= "> Upload folder wipe completed\n";

    echo json_encode([
        'success' => true,
        'message' => $message
    ]);
}

/**
 * Self-destruct - complete system reset
 */
function handleSelfDestruct()
{
    $message = "> SELF-DESTRUCT INITIATED\n";
    $message .= "> Step 1: Resetting database...\n";

    $pdo = get_connection();

    try {
        // Read and execute the database schema
        $schemaFile = __DIR__ . '/../../database/database.sql';

        if (!file_exists($schemaFile)) {
            throw new Exception('Database schema file not found');
        }

        $sql = file_get_contents($schemaFile);

        // Execute SQL (split by semicolons)
        $statements = array_filter(array_map('trim', explode(';', $sql)));

        foreach ($statements as $statement) {
            if (!empty($statement)) {
                $pdo->exec($statement);
            }
        }

        $message .= "> Database reset complete\n";

        // Wipe uploads
        $message .= "> Step 2: Wiping uploads...\n";
        $uploadDir = __DIR__ . '/../../uploads/';

        function deleteAll($dir, &$msg)
        {
            if (!is_dir($dir)) return;

            $files = scandir($dir);
            foreach ($files as $file) {
                if ($file === '.' || $file === '..' || $file === '.htaccess') continue;

                $path = $dir . '/' . $file;
                if (is_file($path)) {
                    unlink($path);
                } elseif (is_dir($path)) {
                    deleteAll($path, $msg);
                    rmdir($path);
                }
            }
        }

        deleteAll($uploadDir, $message);

        // Recreate upload directories
        $dirs = [
            $uploadDir . 'products',
            $uploadDir . 'identity_proofs'
        ];

        foreach ($dirs as $dir) {
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
        }

        $message .= "> Uploads wiped\n";
        $message .= "> SELF-DESTRUCT COMPLETE\n";
        $message .= "> System has been reset to factory defaults\n";

        // Clear system manager auth
        unset($_SESSION['system_manager_auth']);

        echo json_encode([
            'success' => true,
            'message' => $message
        ]);
    } catch (Exception $e) {
        throw $e;
    }
}

/**
 * Reset user password
 */
function handlePasswordReset()
{
    $userId = $_POST['user_id'] ?? null;

    if (!$userId) {
        throw new Exception('User ID required');
    }

    // Get user
    $user = fetch_one("SELECT * FROM users WHERE user_id = ?", [$userId]);

    if (!$user) {
        throw new Exception('User not found');
    }

    // Generate new random password
    $newPassword = 'Pass' . rand(1000, 9999) . '!';
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Update password
    execute_query("UPDATE users SET password = ? WHERE user_id = ?", [$hashedPassword, $userId]);

    $message = "> Password reset for user: {$user['name']} ({$user['email']})\n";
    $message .= "> New password: $newPassword\n";

    echo json_encode([
        'success' => true,
        'message' => $message,
        'new_password' => $newPassword,
        'user' => $user['name']
    ]);
}

/**
 * Generate seed data
 */
function handleGenerateSeed()
{
    require_once __DIR__ . '/../../includes/seed_generator.php';

    $message = "> Starting seed data generation...\n";

    try {
        $result = generateSeedData();

        $message .= "> SQL file created: {$result['sql_file']}\n";
        $message .= "> Total products: {$result['product_count']}\n";
        $message .= "> Images downloaded: {$result['image_count']}\n";

        if (isset($result['package_file'])) {
            $message .= "> Package created: {$result['package_file']}\n";
        }

        echo json_encode([
            'success' => true,
            'message' => $message,
            'download_url' => $result['download_url'] ?? null
        ]);
    } catch (Exception $e) {
        throw $e;
    }
}
