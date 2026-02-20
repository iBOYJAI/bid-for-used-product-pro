<?php

/**
 * Login Diagnostic Tool
 * Run this in your browser to check database and credentials.
 */

require_once __DIR__ . '/includes/database.php';
require_once __DIR__ . '/includes/validation.php';

header('Content-Type: text/plain');

echo "--- LOGIN DIAGNOSTIC START ---" . PHP_EOL;

// 1. Connection Test
try {
    $pdo = get_connection();
    echo "[OK] Connected to database: " . DB_NAME . PHP_EOL;
} catch (Exception $e) {
    die("[FAIL] Database connection failed: " . $e->getMessage());
}

// 2. Check Admin User
$admin_email = 'admin@example.com';
$user = fetch_one("SELECT * FROM users WHERE email = ?", [$admin_email]);

if (!$user) {
    echo "[FAIL] User '$admin_email' NOT found in database." . PHP_EOL;
    echo "       Please run 'setup_project.php' to initialize the database." . PHP_EOL;
} else {
    echo "[OK] Found user: " . $user['name'] . " (Role: " . $user['role'] . ")" . PHP_EOL;

    // 3. Test Password Verification
    $test_pass = 'admin123';
    if (password_verify($test_pass, $user['password'])) {
        echo "[OK] Password verification successful for '$test_pass'." . PHP_EOL;
    } else {
        echo "[FAIL] Password verification FAILED for '$test_pass'." . PHP_EOL;
        echo "       Existing Hash in DB: " . $user['password'] . PHP_EOL;
        echo "       Expected Hash Sample: " . password_hash($test_pass, PASSWORD_BCRYPT) . PHP_EOL;
    }
}

// 4. Check Demo Users
$roles = [
    'company' => 'company123',
    'client' => 'client123'
];

foreach ($roles as $role => $pass) {
    $u = fetch_one("SELECT email, password FROM users WHERE role = ? LIMIT 1", [$role]);
    if ($u) {
        $verify = password_verify($pass, $u['password']) ? "[OK]" : "[FAIL]";
        echo "$verify Demo $role: " . $u['email'] . PHP_EOL;
        if ($verify === "[FAIL]") {
            echo "       Verification failed for '$pass'. Hash in DB: " . $u['password'] . PHP_EOL;
        }
    } else {
        echo "[FAIL] No demo $role found." . PHP_EOL;
    }
}

echo "--- DIAGNOSTIC END ---" . PHP_EOL;
echo "If you see any [FAIL], please run 'http://localhost/bid_for_used_product/setup_project.php' first." . PHP_EOL;
echo "The setup script now has detailed error reporting enabled to help us if it fails." . PHP_EOL;
