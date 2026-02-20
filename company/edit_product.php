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

    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
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
            // Refresh data
            $product = fetch_one("SELECT * FROM products WHERE product_id = ?", [$product_id]);
        } else {
            $error = "Failed to update product.";
        }
    }
}

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
                <h3 style="margin-bottom: 1rem; font-size: 1.1rem;">Product Image</h3>
                <div style="margin-bottom: 1.5rem; border-radius: 8px; overflow: hidden; height: 250px; background: #f3f4f6; display: flex; align-items: center; justify-content: center;">
                    <?php
                    $img_src = $product['product_image']
                        ? APP_URL . '/uploads/products/' . $product['product_image']
                        : APP_URL . '/assets/images/Notion-Resources/Office-Club/Regular/png/oc-puzzle.png';
                    ?>
                    <img src="<?php echo $img_src; ?>"
                        alt="Product"
                        style="width: 100%; height: 100%; object-fit: cover; <?php echo $product['product_image'] ? '' : 'padding: 2rem; object-fit: contain;'; ?>"
                        id="imagePreview">
                </div>

                <label class="btn btn-secondary w-full" style="cursor: pointer; display: block;">
                    Change Image
                    <input type="file" name="product_image" form="editForm" style="display: none;" onchange="previewImage(this)">
                </label>
                <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.5rem;">Click 'Update Details' to save changes.</p>
            </div>
        </div>
    </div>
</div>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('imagePreview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>