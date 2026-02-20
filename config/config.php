<?php

/**
 * BID FOR USED PRODUCT - Configuration File
 * Database connection and application settings
 */

// Load error handler first to catch any errors
require_once __DIR__ . '/../includes/error_handler.php';

// Error Reporting (set to 0 in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);
define('DISPLAY_ERRORS', true); // Used by error handler

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'bid_for_used_product');

// Application Settings
define('APP_NAME', 'BID FOR USED PRODUCT');
define('APP_URL', 'http://localhost/bid_for_used_product');

// Upload Directories
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('IDENTITY_PROOF_DIR', UPLOAD_DIR . 'identity_proofs/');
define('PRODUCT_IMAGE_DIR', UPLOAD_DIR . 'products/');

// File Upload Settings
define('MAX_FILE_SIZE', 5242880); // 5MB in bytes
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/jpg', 'image/png']);
define('ALLOWED_DOCUMENT_TYPES', ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png']);

// Session Configuration
define('SESSION_LIFETIME', 1800); // 30 minutes in seconds

// Security Settings
define('PASSWORD_MIN_LENGTH', 8);

// Timezone
date_default_timezone_set('Asia/Kolkata');

// Create upload directories if they don't exist
if (!file_exists(IDENTITY_PROOF_DIR)) {
    mkdir(IDENTITY_PROOF_DIR, 0755, true);
}

if (!file_exists(PRODUCT_IMAGE_DIR)) {
    mkdir(PRODUCT_IMAGE_DIR, 0755, true);
}
