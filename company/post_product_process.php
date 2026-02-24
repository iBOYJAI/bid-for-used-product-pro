<?php

/**
 * Post Product Process Handler
 */

require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/validation.php';

require_login('company');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: post_product.php');
    exit;
}

$company_id = get_company_id();

// Sanitize inputs
$product_name = sanitize_input($_POST['product_name']);
$category = sanitize_input($_POST['category']);
$model = sanitize_input($_POST['model']);
$year = (int)$_POST['year'];
$chassis_no = sanitize_input($_POST['chassis_no'] ?? '');
$owner_details = sanitize_input($_POST['owner_details']);
$running_duration = sanitize_input($_POST['running_duration']);
$base_price = (float)$_POST['base_price'];
$description = sanitize_input($_POST['description']);
$bid_start = $_POST['bid_start'];
$bid_end = $_POST['bid_end'];

// Validate inputs
$errors = [];

if (empty($product_name) || empty($category) || empty($model) || empty($owner_details) || empty($running_duration)) {
    $errors[] = 'All required fields must be filled';
}

if ($base_price <= 0) {
    $errors[] = 'Base price must be greater than 0';
}

// Validate dates
$start_timestamp = strtotime($bid_start);
$end_timestamp = strtotime($bid_end);

if ($end_timestamp <= $start_timestamp) {
    header('Location: post_product.php?error=date_error');
    exit;
}

if (!in_array($category, ['2-wheeler', '4-wheeler', 'machinery'])) {
    $errors[] = 'Invalid category';
}

if (!empty($errors)) {
    header('Location: post_product.php?error=validation');
    exit;
}

// Handle file upload (Multiple) - only process actually selected and uploaded files
$product_images = [];
$main_image = null;

// Normalize: PHP may send single file as non-array
$file_names = $_FILES['product_image']['name'] ?? [];
if (!is_array($file_names)) {
    $file_names = $file_names ? [$file_names] : [];
}
$file_count = count($file_names);

if (isset($_FILES['product_image']) && $file_count > 0) {
    $files = $_FILES['product_image'];

    for ($i = 0; $i < $file_count; $i++) {
        // Only process when user actually selected a file: has name, OK error, and file was uploaded
        $name = isset($files['name'][$i]) ? trim($files['name'][$i]) : '';
        $tmp = $files['tmp_name'][$i] ?? '';
        $err = isset($files['error'][$i]) ? $files['error'][$i] : UPLOAD_ERR_NO_FILE;
        if ($name === '' || $err !== UPLOAD_ERR_OK || $tmp === '' || !is_uploaded_file($tmp)) {
            continue;
        }

        $file_array = [
            'name' => basename($name),
            'type' => $files['type'][$i] ?? '',
            'tmp_name' => $tmp,
            'error' => $err,
            'size' => $files['size'][$i] ?? 0
        ];

        list($upload_success, $filename_or_error) = upload_file($file_array, PRODUCT_IMAGE_DIR, ALLOWED_IMAGE_TYPES);

        if ($upload_success) {
            $product_images[] = $filename_or_error;
            if (!$main_image) {
                $main_image = $filename_or_error; // First one is main
            }
        }
    }
}

if (!$main_image) {
    header('Location: post_product.php?error=file_upload_failed');
    exit;
}

// Insert product and gallery
try {
    // 1. Insert Product
    // 1. Insert Product
    $sql = "INSERT INTO products (company_id, product_name, category, model, year, chassis_no, owner_details, running_duration, base_price, description, bid_start, bid_end, product_image, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'open')";

    execute_query($sql, [
        $company_id,
        $product_name,
        $category,
        $model,
        $year,
        $chassis_no,
        $owner_details,
        $running_duration,
        $base_price,
        $description,
        $bid_start,
        $bid_end,
        $main_image
    ]);

    $product_id = get_last_insert_id();

    if ($product_id) {
        // 2. Insert Gallery Images
        if (!empty($product_images)) {
            $gallery_sql = "INSERT INTO product_gallery (product_id, image_path) VALUES (?, ?)";
            foreach ($product_images as $img) {
                execute_query($gallery_sql, [$product_id, $img]);
            }
        }
    }

    header('Location: my_products.php?success=posted');
    exit;
} catch (Exception $e) {
    // Cleanup images on error
    foreach ($product_images as $img) {
        delete_file(PRODUCT_IMAGE_DIR . $img);
    }

    header('Location: post_product.php?error=database');
    exit;
}
