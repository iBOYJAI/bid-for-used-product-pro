<?php

/**
 * BID FOR USED PRODUCT - Database Helper Functions
 * PDO-based database operations with prepared statements
 */

require_once __DIR__ . '/../config/config.php';

/**
 * Get PDO database connection
 * @return PDO connection object
 */
function get_connection()
{
    static $pdo = null;

    if ($pdo === null) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ];
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // Log the detailed error
            if (function_exists('log_error')) {
                log_error("Database connection failed", [
                    'host' => DB_HOST,
                    'database' => DB_NAME,
                    'error' => $e->getMessage()
                ]);
            }

            // Show user-friendly error with helpful info
            $error_html = '
            <!DOCTYPE html>
            <html><head><meta charset="UTF-8"><title>Database Error</title>
            <style>body{font-family:Arial;padding:40px;background:#f5f5f5;}
            .error{background:white;padding:30px;border-radius:8px;max-width:600px;margin:0 auto;box-shadow:0 2px 10px rgba(0,0,0,0.1);}
            h1{color:#e53e3e;}code{background:#f7fafc;padding:2px 6px;border-radius:3px;}</style>
            </head><body><div class="error">
            <h1>⚠️ Database Connection Failed</h1>
            <p><strong>Cannot connect to MySQL database.</strong></p>
            <p>Error: ' . htmlspecialchars($e->getMessage()) . '</p>
            <hr style="margin:20px 0;border:none;border-top:1px solid #e2e8f0;">
            <p><strong>Quick Fixes:</strong></p>
            <ol>
                <li>Make sure <strong>XAMPP MySQL</strong> is running (green in XAMPP Control Panel)</li>
                <li>Verify database <code>bid_for_used_product</code> exists in phpMyAdmin</li>
                <li>Check database was imported from <code>database/database.sql</code></li>
                <li>Run the <a href="/bid_for_used_product/setup_wizard.php">Setup Wizard</a></li>
            </ol>
            <p style="margin-top:20px;">
                <a href="/bid_for_used_product/check_system.php" style="background:#667eea;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;display:inline-block;">Run System Check</a>
            </p>
            </div></body></html>';

            die($error_html);
        }
    }

    return $pdo;
}

/**
 * Test database connection
 * @return array [bool success, string message]
 */
function test_database_connection()
{
    try {
        $pdo = get_connection();
        $pdo->query("SELECT 1");
        return [true, "Database connection successful"];
    } catch (Exception $e) {
        return [false, "Database connection failed: " . $e->getMessage()];
    }
}

/**
 * Check if database tables exist
 * @return array [bool success, array missing_tables]
 */
function check_required_tables()
{
    $required_tables = ['users', 'companies', 'products', 'bids', 'subscriptions'];
    $missing_tables = [];

    try {
        $pdo = get_connection();
        $stmt = $pdo->query("SHOW TABLES");
        $existing_tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

        foreach ($required_tables as $table) {
            if (!in_array($table, $existing_tables)) {
                $missing_tables[] = $table;
            }
        }

        return [empty($missing_tables), $missing_tables];
    } catch (Exception $e) {
        return [false, $required_tables];
    }
}

/**
 * Execute a query with prepared statement
 * @param string $sql SQL query with placeholders
 * @param array $params Parameters to bind
 * @return PDOStatement
 */
function execute_query($sql, $params = [])
{
    $pdo = get_connection();
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}

/**
 * Fetch single row from database
 * @param string $sql SQL query
 * @param array $params Parameters
 * @return array|false
 */
function fetch_one($sql, $params = [])
{
    $stmt = execute_query($sql, $params);
    return $stmt->fetch();
}

/**
 * Fetch all rows from database
 * @param string $sql SQL query
 * @param array $params Parameters
 * @return array
 */
function fetch_all($sql, $params = [])
{
    $stmt = execute_query($sql, $params);
    return $stmt->fetchAll();
}

/**
 * Get last inserted ID
 * @return string
 */
function get_last_insert_id()
{
    $pdo = get_connection();
    return $pdo->lastInsertId();
}

/**
 * Begin transaction
 */
function begin_transaction()
{
    $pdo = get_connection();
    $pdo->beginTransaction();
}

/**
 * Commit transaction
 */
function commit_transaction()
{
    $pdo = get_connection();
    $pdo->commit();
}

/**
 * Rollback transaction
 */
function rollback_transaction()
{
    $pdo = get_connection();
    $pdo->rollBack();
}
