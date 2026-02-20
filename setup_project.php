<?php
// setup_project.php

$host = 'localhost';
$db   = 'bid_for_used_product';
$user = 'root';
$pass = '';

$rootDir = __DIR__;
$downloadsDir = $rootDir . '/downloads/product_images';
$uploadsDir = $rootDir . '/uploads/products';
$seedFile = $rootDir . '/database/comprehensive_seed.sql';
$schemaFile = $rootDir . '/database/database.sql';

// Disable Exceptions
$driver = new mysqli_driver();
$driver->report_mode = MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT;


// Check prerequisites
if (!file_exists($seedFile) || !file_exists($schemaFile)) {
    die("❌ Error: Database SQL files missing in /database/ folder.");
}

// Create Uploads Directory
if (!is_dir($uploadsDir)) {
    mkdir($uploadsDir, 0777, true);
}

// Database Connection
$mysqli = new mysqli($host, $user, $pass);
if ($mysqli->connect_error) {
    die("❌ Connection failed: " . $mysqli->connect_error);
}

echo "<pre>";
echo "🚀 <b>Starting System Initialization</b>\n\n";

// 1. Database Creation
echo "1️⃣  Creating Database '$db'...\n";
$mysqli->query("DROP DATABASE IF EXISTS `$db`");
if (!$mysqli->query("CREATE DATABASE `$db` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci")) {
    die("Error creating DB: " . $mysqli->error);
}
$mysqli->select_db($db);

// 2. Schema Import
echo "2️⃣  Importing Table Schema...\n";
$schemaSql = file_get_contents($schemaFile);
$statements = explode(";", $schemaSql);
foreach ($statements as $stmt) {
    if (trim($stmt) !== '') {
        if (!$mysqli->query($stmt)) {
            echo "   ❌ <b>Schema Error:</b> " . $mysqli->error . "\n";
            echo "      <small>" . htmlspecialchars(substr($stmt, 0, 100)) . "...</small>\n";
        }
    }
}
echo "   ✅ Tables created successfully.\n";

// 3. Seed Data & Image Processing
echo "3️⃣  Seeding Data & Linking Images...\n";
$seedSql = file_get_contents($seedFile);
$lines = explode("\n", $seedSql);
$newLines = [];
$prodCounter = 0;

foreach ($lines as $line) {
    $trimmed = trim($line);
    // Skip comments
    if (strpos($trimmed, '--') === 0 || $trimmed === '') {
        continue;
    }

    // Image Handling logic
    if (preg_match("/'([^']+\.(?:jpg|jpeg|png|JPG))'/", $line, $matches) && strpos($line, 'INSERT INTO products') === false) {
        $prodCounter++;
        $oldImg = $matches[1];
        $newImg = "product_{$prodCounter}.jpg";
        $line = str_replace("'$oldImg'", "'$newImg'", $line);

        $srcDir = $downloadsDir . "/product_{$prodCounter}";
        if (is_dir($srcDir)) {
            $files = glob($srcDir . "/*.{jpg,jpeg,png,JPG}", GLOB_BRACE);
            if ($files && isset($files[0])) {
                copy($files[0], $uploadsDir . "/" . $newImg);

                // Copy Gallery Images
                $idx = 0;
                foreach ($files as $gFile) {
                    if ($idx >= 10) break;
                    $ext = pathinfo($gFile, PATHINFO_EXTENSION);
                    $gName = "gallery_{$prodCounter}_{$idx}.{$ext}";
                    copy($gFile, $uploadsDir . "/" . $gName);
                    $idx++;
                }
            }
        }
    }
    $newLines[] = $line;
}

$finalSeed = implode("\n", $newLines);

// Execute Cleaned Seed - Statement by Statement
$statements = explode(";", $finalSeed);
foreach ($statements as $i => $stmt) {
    $stmt = trim($stmt);
    if ($stmt !== '') {
        if (!$mysqli->query($stmt)) {
            echo "   ❌ <b>Query Error [$i]:</b> " . $mysqli->error . "\n";
            echo "      <small>" . htmlspecialchars(substr($stmt, 0, 200)) . "...</small>\n";
        }
    }
}
echo "   ✅ Data seeded successfully.\n";

// 4. Populate Product Gallery Table
echo "4️⃣  Building Product Galleries...\n";
$mysqli->query("TRUNCATE TABLE product_gallery");

$dirs = glob($downloadsDir . '/product_*', GLOB_ONLYDIR);
$totalG = 0;

foreach ($dirs as $dir) {
    $dirname = basename($dir);
    if (preg_match('/^product_(\d+)$/', $dirname, $matches)) {
        $pid = (int)$matches[1];
        $files = glob($dir . '/*.{jpg,jpeg,png,JPG}', GLOB_BRACE);
        $idx = 0;
        foreach ($files as $f) {
            if ($idx >= 10) break;
            $ext = pathinfo($f, PATHINFO_EXTENSION);
            $gName = "gallery_{$pid}_{$idx}.{$ext}";
            $mysqli->query("INSERT INTO product_gallery (product_id, image_path) VALUES ($pid, '$gName')");
            $idx++;
            $totalG++;
        }
    }
}
echo "   ✅ Gallery built with $totalG images.\n";

$mysqli->close();

echo "\n✨ <b>SYSTEM READY!</b> ✨\n";
echo "You can now login as:\n";
echo " - Admin: admin@example.com (admin123)\n";
echo "</pre>";
