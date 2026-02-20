<?php

/**
 * Notification Helper Functions
 */

require_once __DIR__ . '/database.php';

/**
 * Get notifications for a user
 * @param int $user_id
 * @param int $limit
 * @return array
 */
function get_notifications($user_id, $limit = 20)
{
    $sql = "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT ?";

    // PDO limit requires integer binding or direct injection if safe
    // For simplicity with our helper, we'll append standard limit if logic permits, 
    // but our execute_query helper binds everything as string/inferred.
    // Let's use direct query for LIMIT or just fetch all and slice in PHP if small.
    // Better: Use the execute_query but cast limit.

    // Actually, let's just use string interpolation for LIMIT since it's an int parameter here
    $limit = (int)$limit;
    $sql = "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT $limit";

    return fetch_all($sql, [$user_id]);
}

/**
 * Get unread notification count
 * @param int $user_id
 * @return int
 */
function get_unread_count($user_id)
{
    $result = fetch_one("SELECT COUNT(*) as count FROM notifications WHERE user_id = ? AND is_read = 0", [$user_id]);
    return $result ? (int)$result['count'] : 0;
}

/**
 * Create a new notification
 * @param int $user_id
 * @param string $title
 * @param string $message
 * @param string $type
 * @param string|null $target_url
 * @return bool
 */
function create_notification($user_id, $title, $message, $type = 'info', $target_url = null)
{
    $sql = "INSERT INTO notifications (user_id, title, message, type, target_url, created_at) VALUES (?, ?, ?, ?, ?, NOW())";
    try {
        execute_query($sql, [$user_id, $title, $message, $type, $target_url]);
        return true;
    } catch (Exception $e) {
        // Silently fail for notifications to not disrupt main flow
        return false;
    }
}

/**
 * Mark a notification as read
 * @param int $notification_id
 * @param int $user_id Security check
 * @return bool
 */
function mark_as_read($notification_id, $user_id)
{
    $sql = "UPDATE notifications SET is_read = 1 WHERE notification_id = ? AND user_id = ?";
    execute_query($sql, [$notification_id, $user_id]);
    return true;
}

/**
 * Mark all notifications as read for a user
 * @param int $user_id
 * @return bool
 */
function mark_all_as_read($user_id)
{
    $sql = "UPDATE notifications SET is_read = 1 WHERE user_id = ?";
    execute_query($sql, [$user_id]);
    return true;
}
