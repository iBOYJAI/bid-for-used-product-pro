<?php

/**
 * BID FOR USED PRODUCT - Validation and File Upload Helpers
 * Input sanitization and file handling functions
 */

require_once __DIR__ . '/../config/config.php';

/**
 * Sanitize input data
 * @param mixed $data Input data
 * @return mixed Sanitized data
 */
function sanitize_input($data)
{
    if (is_array($data)) {
        return array_map('sanitize_input', $data);
    }
    return strip_tags(trim($data));
}

/**
 * Validate email format
 * @param string $email Email address
 * @return bool
 */
function validate_email($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate phone number (Indian format)
 * @param string $phone Phone number
 * @return bool
 */
function validate_phone($phone)
{
    return preg_match('/^[6-9]\d{9}$/', $phone);
}

/**
 * Validate password strength
 * @param string $password Password
 * @return array [bool success, string message]
 */
function validate_password($password)
{
    if (strlen($password) < PASSWORD_MIN_LENGTH) {
        return [false, "Password must be at least " . PASSWORD_MIN_LENGTH . " characters long"];
    }
    return [true, ""];
}

/**
 * Hash password securely
 * @param string $password Plain text password
 * @return string Hashed password
 */
function hash_password($password)
{
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * Verify password against hash
 * @param string $password Plain text password
 * @param string $hash Hashed password
 * @return bool
 */
function verify_password($password, $hash)
{
    return password_verify($password, $hash);
}

/**
 * Validate file upload
 * @param array $file $_FILES array element
 * @param array $allowed_types Allowed MIME types
 * @param int $max_size Maximum file size in bytes
 * @return array [bool success, string message]
 */
function validate_file($file, $allowed_types = ALLOWED_IMAGE_TYPES, $max_size = MAX_FILE_SIZE)
{
    // Check if file was uploaded
    if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
        return [false, "No file uploaded"];
    }

    // Check file size
    if ($file['size'] > $max_size) {
        $max_mb = $max_size / 1048576;
        return [false, "File size exceeds maximum limit of {$max_mb}MB"];
    }

    // Check file type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mime_type, $allowed_types)) {
        return [false, "File type not allowed"];
    }

    // Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return [false, "File upload error"];
    }

    return [true, ""];
}

/**
 * Upload file to specified directory
 * @param array $file $_FILES array element
 * @param string $destination_dir Destination directory path
 * @param array $allowed_types Allowed MIME types
 * @return array [bool success, string message/filename]
 */
function upload_file($file, $destination_dir, $allowed_types = ALLOWED_IMAGE_TYPES)
{
    // Validate file
    list($valid, $error) = validate_file($file, $allowed_types);
    if (!$valid) {
        return [false, $error];
    }

    // Generate unique filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '_' . time() . '.' . $extension;
    $destination = $destination_dir . $filename;

    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return [true, $filename];
    } else {
        return [false, "Failed to move uploaded file"];
    }
}

/**
 * Delete file from server
 * @param string $filepath Full file path
 * @return bool
 */
function delete_file($filepath)
{
    if (file_exists($filepath)) {
        return unlink($filepath);
    }
    return false;
}

/**
 * Generate random string
 * @param int $length Length of string
 * @return string
 */
function generate_random_string($length = 10)
{
    return bin2hex(random_bytes($length / 2));
}

/**
 * Helper functions for formatting are now in functions.php
 */
require_once __DIR__ . '/functions.php';
