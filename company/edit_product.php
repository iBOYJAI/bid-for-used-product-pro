<?php
$page_title = 'Edit Product';
require_once __DIR__ . '/../includes/header.php';
require_login('company');

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$company_id = get_company_id();

// Fetch product
$product = fetch_one("SELECT * FROM products WHERE product_id = ? AND company_id = ?", [$product_id, $company_id]);

if (!$product) {
    echo '<div class="container"><div class="alert alert-error">Product not found.</div></div>';
    require_once __DIR__ . '/../includes/footer.php';
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = sanitize_input($_POST['product_name']);
    $model = sanitize_input($_POST['model']);
    $year = sanitize_input($_POST['year']);
    $base_price = sanitize_input($_POST['base_price']);
    $running_duration = sanitize_input($_POST['running_duration']);
    $description = sanitize_input($_POST['description']);
    $status = sanitize_input($_POST['status']);

    // Convert 'T' format back to mysql format
    $bid_end = date('Y-m-d H:i:s', strtotime($_POST['bid_end']));

    // Handle Image Upload
    $image_query_part = "";
    $params = [$product_name, $model, $year, $base_price, $running_duration, $description, $status, $bid_end];

    // Only update main image when user actually selected and uploaded a file
    if (isset($_FILES['product_image']['tmp_name']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK
        && !empty($_FILES['product_image']['tmp_name']) && is_uploaded_file($_FILES['product_image']['tmp_name'])) {
        $upload = upload_file($_FILES['product_image'], UPLOAD_DIR . 'products/');
        if ($upload[0]) {
            $image_query_part = ", product_image = ?";
            $params[] = $upload[1]; // Add new image name to params
        } else {
            $error = $upload[1];
        }
    }

    if (!$error) {
        // Update query
        $sql = "UPDATE products SET 
                product_name = ?, model = ?, year = ?, base_price = ?, 
                running_duration = ?, description = ?, status = ?, bid_end = ? 
                $image_query_part
                WHERE product_id = ? AND company_id = ?";

        // Add ID and CompanyID to params
        $params[] = $product_id;
        $params[] = $company_id;

        $stmt = execute_query($sql, $params);

        if ($stmt) {
            $success = "Product updated successfully!";

            // Handle additional gallery images only when user actually selected file(s)
            $gallery_names = $_FILES['gallery_images']['name'] ?? null;
            if (isset($_FILES['gallery_images']) && $gallery_names !== null) {
                if (!is_array($gallery_names)) {
                    $gallery_names = $gallery_names ? [$gallery_names] : [];
                }
                $files = $_FILES['gallery_images'];
                $upload_dir = UPLOAD_DIR . 'products/';
                $added = 0;
                for ($i = 0; $i < count($gallery_names); $i++) {
                    $name = isset($files['name'][$i]) ? trim($files['name'][$i]) : '';
                    $tmp = $files['tmp_name'][$i] ?? '';
                    $err = isset($files['error'][$i]) ? $files['error'][$i] : UPLOAD_ERR_NO_FILE;
                    // Skip: no file selected, upload error, or not actually uploaded
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
                    list($upload_ok, $filename_or_error) = upload_file($file_array, $upload_dir, ALLOWED_IMAGE_TYPES);
                    if ($upload_ok) {
                        execute_query("INSERT INTO product_gallery (product_id, image_path) VALUES (?, ?)", [$product_id, $filename_or_error]);
                        $added++;
                    }
                }
                if ($added > 0) {
                    $success .= " $added extra image(s) added to gallery.";
                }
            }

            // Refresh data
            $product = fetch_one("SELECT * FROM products WHERE product_id = ?", [$product_id]);
        } else {
            $error = "Failed to update product.";
        }
    }
}

// Load existing gallery for display
$existing_gallery = fetch_all("SELECT image_path FROM product_gallery WHERE product_id = ? ORDER BY gallery_id ASC", [$product_id]);

// Prepare date for input type=datetime-local
$bid_end_val = date('Y-m-d\TH:i', strtotime($product['bid_end']));
?>

<div class="container" style="padding: 2rem 1.5rem;">
    <div class="flex-between" style="margin-bottom: 2rem;">
        <h1 style="font-size: 1.75rem;">Edit Product</h1>
        <a href="my_products.php" class="btn btn-secondary">&larr; Back to My Products</a>
    </div>

    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>

    <div class="card" style="max-width: 900px; margin: 0 auto; display: grid; grid-template-columns: 1fr 350px; gap: 2rem;">
        <!-- Form Section -->
        <form action="" method="POST" enctype="multipart/form-data" id="editForm">
            <div class="form-group">
                <label class="form-label">Product Name</label>
                <input type="text" name="product_name" class="form-control" require value="<?php echo htmlspecialchars($product['product_name']); ?>">
            </div>

            <div class="grid-3" style="grid-template-columns: 1fr 1fr; margin-bottom: 1.5rem;">
                <div>
                    <label class="form-label">Model</label>
                    <input type="text" name="model" class="form-control" required value="<?php echo htmlspecialchars($product['model']); ?>">
                </div>
                <div>
                    <label class="form-label">Year</label>
                    <input type="number" name="year" class="form-control" required value="<?php echo $product['year']; ?>">
                </div>
            </div>

            <div class="grid-3" style="grid-template-columns: 1fr 1fr; margin-bottom: 1.5rem;">
                <div>
                    <label class="form-label">Base Price</label>
                    <input type="number" name="base_price" class="form-control" required step="0.01" value="<?php echo $product['base_price']; ?>">
                </div>
                <div>
                    <label class="form-label">Running Duration</label>
                    <input type="text" name="running_duration" class="form-control" required value="<?php echo htmlspecialchars($product['running_duration']); ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="6" required><?php echo htmlspecialchars($product['description']); ?></textarea>
            </div>

            <div class="grid-3" style="grid-template-columns: 1fr 1fr; margin-bottom: 2rem;">
                <div>
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="open" <?php echo $product['status'] === 'open' ? 'selected' : ''; ?>>Open</option>
                        <option value="closed" <?php echo $product['status'] === 'closed' ? 'selected' : ''; ?>>Closed (Ended)</option>
                        <option value="sold" <?php echo $product['status'] === 'sold' ? 'selected' : ''; ?>>Sold</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Extend/Change End Date</label>
                    <input type="datetime-local" name="bid_end" class="form-control" required value="<?php echo $bid_end_val; ?>">
                </div>
            </div>

            <button type="submit" class="btn btn-success w-full">Update Details</button>
        </form>

        <!-- Image Preview Section -->
        <div>
            <div class="card" style="padding: 1.5rem; text-align: center;">
                <h3 style="margin-bottom: 1rem; font-size: 1.1rem;">Main Product Image</h3>
                <div style="margin-bottom: 1.5rem; border-radius: 8px; overflow: hidden; height: 250px; background: #f3f4f6; display: flex; align-items: center; justify-content: center;">
                    <?php if ($product['product_image']): ?>
                        <img src="<?php echo APP_URL . '/uploads/products/' . $product['product_image']; ?>"
                            alt="Product" style="width: 100%; height: 100%; object-fit: cover;" id="imagePreview">
                    <?php else: ?>
                        <span style="color: var(--text-muted); font-size: 0.9rem;">No image uploaded</span>
                        <img src="" alt="" id="imagePreview" style="display: none;">
                    <?php endif; ?>
                </div>

                <label class="btn btn-secondary w-full" style="cursor: pointer; display: block;">
                    Change Main Image
                    <input type="file" name="product_image" form="editForm" style="display: none;" onchange="previewImage(this)" accept="image/*">
                </label>

                <h3 style="margin: 1.5rem 0 0.75rem; font-size: 1rem;">Gallery (extra images)</h3>
                <?php if (!empty($existing_gallery)): ?>
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(60px, 1fr)); gap: 0.5rem; margin-bottom: 1rem;">
                        <?php foreach ($existing_gallery as $g): ?>
                            <img src="<?php echo APP_URL; ?>/uploads/products/<?php echo htmlspecialchars($g['image_path']); ?>" alt="" style="width: 100%; aspect-ratio: 1; object-fit: cover; border-radius: 6px; border: 1px solid #e2e8f0;">
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.75rem;">No extra images yet. Add some below.</p>
                <?php endif; ?>

                <label class="btn btn-primary w-full" style="cursor: pointer; display: block;">
                    Add More Images
                    <input type="file" name="gallery_images[]" form="editForm" style="display: none;" accept="image/*" multiple>
                </label>
                <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.5rem;">Optional. Select multiple images, then click Update Details.</p>
            </div>
        </div>
    </div>
</div>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var preview = document.getElementById('imagePreview');
            var container = preview.closest('div');
            var noImg = container.querySelector('span');
            if (noImg) noImg.style.display = 'none';
            preview.style.display = 'block';
            preview.style.width = '100%';
            preview.style.height = '100%';
            preview.style.objectFit = 'cover';
            var reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>