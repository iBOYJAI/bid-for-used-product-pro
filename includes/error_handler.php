<?php
/**
 * BID FOR USED PRODUCT - Custom Error Handler
 * Prevents white pages by logging errors and showing user-friendly messages
 */

// Error log file location
define('ERROR_LOG_FILE', __DIR__ . '/../logs/error_log.txt');

// Create logs directory if it doesn't exist
if (!file_exists(__DIR__ . '/../logs')) {
    mkdir(__DIR__ . '/../logs', 0755, true);
}

/**
 * Custom error handler function
 */
function custom_error_handler($errno, $errstr, $errfile, $errline) {
    $error_message = date('Y-m-d H:i:s') . " | ";
    $error_message .= "Error [$errno]: $errstr in $errfile on line $errline\n";
    
    // Log to file
    error_log($error_message, 3, ERROR_LOG_FILE);
    
    // For fatal errors, show user-friendly message
    if ($errno == E_ERROR || $errno == E_USER_ERROR) {
        show_error_page($errstr, $errfile, $errline);
        exit;
    }
    
    return true;
}

/**
 * Custom exception handler
 */
function custom_exception_handler($exception) {
    $error_message = date('Y-m-d H:i:s') . " | ";
    $error_message .= "Exception: " . $exception->getMessage() . " in ";
    $error_message .= $exception->getFile() . " on line " . $exception->getLine() . "\n";
    
    // Log to file
    error_log($error_message, 3, ERROR_LOG_FILE);
    
    // Show user-friendly error page
    show_error_page(
        $exception->getMessage(), 
        $exception->getFile(), 
        $exception->getLine()
    );
}

/**
 * Shutdown handler to catch fatal errors
 */
function shutdown_handler() {
    $error = error_get_last();
    if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        $error_message = date('Y-m-d H:i:s') . " | ";
        $error_message .= "Fatal Error [{$error['type']}]: {$error['message']} ";
        $error_message .= "in {$error['file']} on line {$error['line']}\n";
        
        // Log to file
        error_log($error_message, 3, ERROR_LOG_FILE);
        
        // Show user-friendly error page
        show_error_page($error['message'], $error['file'], $error['line']);
    }
}

/**
 * Display user-friendly error page
 */
function show_error_page($message, $file, $line) {
    // Clear any previous output
    if (ob_get_length()) ob_clean();
    
    $display_details = (defined('DISPLAY_ERRORS') && DISPLAY_ERRORS) || 
                       (ini_get('display_errors') == '1');
    
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>System Error - BID FOR USED PRODUCT</title>
        <style>
            * { margin: 0; padding: 0; box-sizing: border-box; }
            body {
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-Content: center;
                padding: 20px;
            }
            .error-container {
                background: white;
                border-radius: 12px;
                box-shadow: 0 10px 40px rgba(0,0,0,0.2);
                max-width: 600px;
                width: 100%;
                padding: 40px;
                text-align: center;
            }
            .error-icon {
                font-size: 64px;
                margin-bottom: 20px;
            }
            h1 {
                color: #e53e3e;
                font-size: 28px;
                margin-bottom: 15px;
            }
            .error-message {
                color: #4a5568;
                font-size: 16px;
                line-height: 1.6;
                margin-bottom: 25px;
            }
            .error-details {
                background: #f7fafc;
                border-left: 4px solid #e53e3e;
                padding: 15px;
                margin: 20px 0;
                text-align: left;
                font-family: monospace;
                font-size: 13px;
                color: #2d3748;
                overflow-x: auto;
            }
            .btn {
                display: inline-block;
                background: #667eea;
                color: white;
                padding: 12px 30px;
                border-radius: 6px;
                text-decoration: none;
                margin: 5px;
                transition: background 0.3s;
            }
            .btn:hover {
                background: #5568d3;
            }
            .btn-secondary {
                background: #718096;
            }
            .btn-secondary:hover {
                background: #5a6778;
            }
            .help-text {
                margin-top: 25px;
                padding-top: 25px;
                border-top: 1px solid #e2e8f0;
                color: #718096;
                font-size: 14px;
            }
        </style>
    </head>
    <body>
        <div class="error-container">
            <div class="error-icon">⚠️</div>
            <h1>Oops! Something Went Wrong</h1>
            <p class="error-message">
                The system encountered an error and couldn't complete your request.
                This error has been logged for investigation.
            </p>
            
            <?php if ($display_details): ?>
                <div class="error-details">
                    <strong>Error Details:</strong><br>
                    Message: <?php echo htmlspecialchars($message); ?><br>
                    File: <?php echo htmlspecialchars($file); ?><br>
                    Line: <?php echo $line; ?>
                </div>
            <?php endif; ?>
            
            <div>
                <a href="<?php echo isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/'; ?>" class="btn btn-secondary">Go Back</a>
                <a href="/bid_for_used_product/" class="btn">Home Page</a>
                <a href="/bid_for_used_product/check_system.php" class="btn">System Check</a>
            </div>
            
            <div class="help-text">
                <strong>Common Solutions:</strong><br>
                1. Make sure XAMPP MySQL service is running<br>
                2. Verify database exists: <code>bid_for_used_product</code><br>
                3. Check error log: <code>logs/error_log.txt</code><br>
                4. Run setup wizard: <code>setup_wizard.php</code>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
}

/**
 * Log custom message to error log
 */
function log_error($message, $context = []) {
    $log_message = date('Y-m-d H:i:s') . " | ";
    $log_message .= $message;
    if (!empty($context)) {
        $log_message .= " | Context: " . json_encode($context);
    }
    $log_message .= "\n";
    
    error_log($log_message, 3, ERROR_LOG_FILE);
}

// Set error handlers
set_error_handler('custom_error_handler');
set_exception_handler('custom_exception_handler');
register_shutdown_function('shutdown_handler');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
