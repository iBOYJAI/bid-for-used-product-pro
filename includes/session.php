<?php

/**
 * BID FOR USED PRODUCT - Session Management
 * Secure session handling and user authentication
 */

require_once __DIR__ . '/../config/config.php';

/**
 * Start secure session
 */
function start_secure_session()
{
    if (session_status() === PHP_SESSION_NONE) {
        ini_set('session.cookie_httponly', 1);
        ini_set('session.use_only_cookies', 1);
        ini_set('session.cookie_secure', 0); // Set to 1 if using HTTPS
        session_start();

        // Session timeout check
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > SESSION_LIFETIME)) {
            session_unset();
            session_destroy();
            session_start();
        }
        $_SESSION['last_activity'] = time();
    }
}

/**
 * Check if user is logged in
 * @return bool
 */
function is_logged_in()
{
    start_secure_session();
    return isset($_SESSION['user_id']) && isset($_SESSION['role']);
}

/**
 * Get current user ID
 * @return int|null
 */
function get_user_id()
{
    start_secure_session();
    return $_SESSION['user_id'] ?? null;
}

/**
 * Get current user role
 * @return string|null
 */
function get_user_role()
{
    start_secure_session();
    return $_SESSION['role'] ?? null;
}

/**
 * Get current user name
 * @return string|null
 */
function get_user_name()
{
    start_secure_session();
    return $_SESSION['name'] ?? null;
}

/**
 * Get company ID (for company users)
 * @return int|null
 */
function get_company_id()
{
    start_secure_session();
    return $_SESSION['company_id'] ?? null;
}

/**
 * Set user session after login
 * @param array $user User data
 */
function set_user_session($user)
{
    start_secure_session();
    session_regenerate_id(true);

    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['name'] = $user['name'];
    $_SESSION['email'] = $user['email'];

    // Set company_id if user is a company
    if ($user['role'] === 'company' && isset($user['company_id'])) {
        $_SESSION['company_id'] = $user['company_id'];
    }
}

/**
 * Require login and optionally check role
 * @param string|array $allowed_roles Single role or array of allowed roles
 */
function require_login($allowed_roles = null)
{
    if (!is_logged_in()) {
        header('Location: ' . APP_URL . '/pages/login.php?error=login_required');
        exit;
    }

    if ($allowed_roles !== null) {
        $current_role = get_user_role();
        $roles = is_array($allowed_roles) ? $allowed_roles : [$allowed_roles];

        if (!in_array($current_role, $roles)) {
            header('Location: ' . APP_URL . '/pages/login.php?error=access_denied');
            exit;
        }
    }
}

/**
 * Logout user
 */
function logout()
{
    start_secure_session();
    session_unset();
    session_destroy();
}

/**
 * Redirect based on user role
 */
function redirect_to_dashboard()
{
    $role = get_user_role();

    switch ($role) {
        case 'admin':
            header('Location: ' . APP_URL . '/admin/dashboard.php');
            break;
        case 'company':
            header('Location: ' . APP_URL . '/company/dashboard.php');
            break;
        case 'client':
            header('Location: ' . APP_URL . '/client/dashboard.php');
            break;
        default:
            header('Location: ' . APP_URL . '/pages/login.php');
    }
    exit;
}

/**
 * Generate CSRF token
 * @return string
 */
function generate_csrf_token()
{
    start_secure_session();
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Validate CSRF token
 * @param string $token Token to validate
 * @return bool
 */
function validate_csrf_token($token)
{
    start_secure_session();
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
