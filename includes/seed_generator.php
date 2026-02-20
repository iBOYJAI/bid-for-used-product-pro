<?php

/**
 * Seed Data Generator
 * Creates comprehensive seed data with product images
 */

/**
 * Generate complete seed data package
 * @return array Results with file paths and counts
 */
function generateSeedData()
{
    // Create downloads directory
    $downloadsDir = __DIR__ . '/../downloads/';
    $imagesDir = $downloadsDir . 'product_images/';

    if (!is_dir($downloadsDir)) {
        mkdir($downloadsDir, 0755, true);
    }

    if (!is_dir($imagesDir)) {
        mkdir($imagesDir, 0755, true);
    }

    // Generate SQL content
    $sql = generateSeedSQL();

    // Save SQL file
    $sqlFile = $downloadsDir . 'seed_data_' . date('Y-m-d_His') . '.sql';
    file_put_contents($sqlFile, $sql);

    // Download product images
    $imageCount = downloadProductImages($imagesDir);

    // Create zip package
    $zipFile = createSeedPackage($downloadsDir, $sqlFile, $imagesDir);

    return [
        'sql_file' => basename($sqlFile),
        'product_count' => 50,
        'image_count' => $imageCount,
        'package_file' => $zipFile ? basename($zipFile) : null,
        'download_url' => $zipFile ? str_replace(__DIR__ . '/..', APP_URL, $zipFile) : null
    ];
}

/**
 * Generate comprehensive seed SQL
 */
function generateSeedSQL()
{
    $sql = "-- ============================================================\n";
    $sql .= "-- BID FOR USED PRODUCT - Comprehensive Seed Data\n";
    $sql .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
    $sql .= "-- ============================================================\n\n";

    $sql .= "USE bid_for_used_product;\n\n";
    $sql .= "SET FOREIGN_KEY_CHECKS = 0;\n\n";

    // Truncate tables
    $sql .= "-- Clear existing data\n";
    $sql .= "TRUNCATE TABLE messages;\n";
    $sql .= "TRUNCATE TABLE notifications;\n";
    $sql .= "TRUNCATE TABLE subscriptions;\n";
    $sql .= "TRUNCATE TABLE bids;\n";
    $sql .= "TRUNCATE TABLE products;\n";
    $sql .= "TRUNCATE TABLE companies;\n";
    $sql .= "DELETE FROM users WHERE user_id > 1;\n\n";

    // Reset auto increment
    $sql .= "ALTER TABLE users AUTO_INCREMENT = 2;\n";
    $sql .= "ALTER TABLE companies AUTO_INCREMENT = 1;\n";
    $sql .= "ALTER TABLE products AUTO_INCREMENT = 1;\n";
    $sql .= "ALTER TABLE bids AUTO_INCREMENT = 1;\n\n";

    // Admin user (already exists as ID 1)
    $sql .= "-- Admin User (ID: 1) already exists\n\n";

    // Companies (Tamil Nadu based)
    $sql .= "-- Company Users and Profiles\n";
    $companies = [
        ['Chennai Auto Hub', 'Rajesh Kumar', 'sales@chennaiauto.com', '9841234567', '23 Anna Salai, Chennai', '33AAACC1234F1Z5'],
        ['Madurai Motors', 'Muthu Pandian', 'info@maduraimotors.com', '9443567890', '45 West Veli Street, Madurai', '33BBBDD5678G2Z6'],
        ['Coimbatore Cars & Bikes', 'Karthik Raja', 'contact@cbecars.com', '9894567890', '89 Avinashi Road, Coimbatore', '33CCCEE9012H3Z7'],
        ['Salem Tractors', 'Periyasamy Gounder', 'sales@salemtractors.com', '9944567890', '12 Omalur Road, Salem', '33DDDFF3456I4Z8'],
        ['Trichy Vehicles', 'Anand Babu', 'deals@trichyvehicles.com', '9787567890', '56 Thillai Nagar, Trichy', '33EEEGG7890J5Z9'],
        ['Erode Machineries', 'Shanmugam', 'erode@machines.com', '9626567890', '78 Brogh Road, Erode', '33FFFHH1234K6Z0'],
        ['Tiruppur Auto Sales', 'Rangasamy', 'auto@tiruppur.com', '9876567890', '12 Cotton Market, Tiruppur', '33HHHII3456L7Z1'],
        ['Vellore Motors', 'Dinesh Kumar', 'bikes@vellore.com', '9876512345', '8 Fort Road, Vellore', '33JJJKK7890M8Z2'],
        ['Thanjavur Vehicles', 'Vijay Sethupathi', 'thanjavur@vehicles.com', '9123456789', 'Big Temple Road, Thanjavur', '33LLLMM1234N9Z3'],
        ['Kanyakumari Heavy Equipment', 'Kumar Raja', 'kk@equipment.com', '9234567890', 'Beach Road, Kanyakumari', '33MMMNN5678O0Z4']
    ];

    $userId = 2;
    $companyId = 1;
    foreach ($companies as $company) {
        $hashedPass = '$2y$10$5B623E3hgfjzbof059l3BO4gewaJxP6b8M5cJuF.HkfWGU/NiKkp2'; // company123


        $sql .= "INSERT INTO users (user_id, role, name, email, password, contact, address, status) VALUES\n";
        $sql .= "($userId, 'company', '{$company[1]}', '{$company[2]}', '$hashedPass', '{$company[3]}', '{$company[4]}', 'active');\n";

        $sql .= "INSERT INTO companies (company_id, user_id, company_name, owner_name, gst_number, verified_status) VALUES\n";
        $sql .= "($companyId, $userId, '{$company[0]}', '{$company[1]}', '{$company[5]}', 'verified');\n\n";

        $userId++;
        $companyId++;
    }

    // Clients (Tamil Nadu based)
    $sql .= "-- Client Users\n";
    $clients = [
        ['Ravi Chandran', 'ravi@gmail.com', '9000011111', 'Kanchipuram'],
        ['Lakshmi Narayanan', 'lakshmi@yahoo.com', '9000022222', 'Tirunelveli'],
        ['Priya Darshini', 'priya@gmail.com', '9000033333', 'Tuticorin'],
        ['Arun Kumar', 'arun@mail.com', '9000044444', 'Vellore'],
        ['Kavitha Ramesh', 'kavitha@gmail.com', '9000055555', 'Dindigul'],
        ['Senthil Balaji', 'senthil@gmail.com', '9111122222', 'Karur'],
        ['Trisha Krishnan', 'trisha@gmail.com', '9333344444', 'Chennai'],
        ['Ajith Kumar', 'ajith@racer.com', '9555566666', 'Madurai'],
        ['Nayanthara Kurup', 'nayanthara@mail.com', '9666677777', 'Coimbatore'],
        ['Suriya Sivakumar', 'suriya@email.com', '9777788888', 'Chennai'],
        ['Jyothika Saravanan', 'jyothika@mail.com', '9888899999', 'Chennai'],
        ['Dhanush Kumar', 'dhanush@email.com', '9999900000', 'Madurai'],
        ['Samantha Ruth', 'samantha@mail.com', '9111100000', 'Chennai'],
        ['Karthi Sivakumar', 'karthi@email.com', '9222211111', 'Trichy'],
        ['Nithya Menen', 'nithya@mail.com', '9333322222', 'Bangalore']
    ];

    foreach ($clients as $client) {
        $hashedPass = '$2y$10$kYMaOpVYB2MWVfN.Bs1uzuzcd6GkIj4d0XCg.YoWKGn0UoORzURV.'; // client123


        $sql .= "INSERT INTO users (user_id, role, name, email, password, contact, address, status) VALUES\n";
        $sql .= "($userId, 'client', '{$client[0]}', '{$client[1]}', '$hashedPass', '{$client[2]}', '{$client[3]}, Tamil Nadu', 'active');\n";

        $userId++;
    }

    $sql .= "\n";

    // Products (50+ diverse products)
    $sql .= "-- Products (50+ Items)\n";
    $products = generateProductData();

    $productId = 1;
    foreach ($products as $product) {
        $companyId = rand(1, 10);
        $basePrice = $product['base_price'];
        $bidStart = "NOW()";
        $bidEnd = "DATE_ADD(NOW(), INTERVAL " . rand(3, 30) . " DAY)";

        $sql .= "INSERT INTO products (product_id, company_id, product_name, category, model, year, chassis_no, owner_details, running_duration, base_price, bid_start, bid_end, product_image, status) VALUES\n";
        $sql .= "($productId, $companyId, '{$product['name']}', '{$product['category']}', '{$product['model']}', {$product['year']}, '{$product['chassis']}', '{$product['owner']}', '{$product['duration']}', $basePrice, $bidStart, $bidEnd, 'product_$productId.jpg', 'open');\n";

        $productId++;
    }

    $sql .= "\n";

    // Bids
    $sql .= "-- Bids (Random realistic bids)\n";
    $bidId = 1;
    for ($i = 1; $i <= 50; $i++) {
        $productId = rand(1, 50);
        $clientId = rand(12, 26); // Client user IDs
        $product = $products[$productId - 1];
        $basePrice = $product['base_price'];
        $bidAmount = $basePrice + rand(1000, 50000);
        $status = rand(1, 10) > 7 ? (rand(0, 1) ? 'approved' : 'rejected') : 'pending';

        $comments = [
            'Interested, cash ready',
            'Can pay advance immediately',
            'Need inspection first',
            'Exchange possible?',
            'Final offer',
            'Best price guaranteed',
            'Immediate settlement',
            'Finance arranged'
        ];

        $comment = $comments[array_rand($comments)];

        $sql .= "INSERT INTO bids (bid_id, product_id, client_id, bid_amount, comments, bid_status) VALUES\n";
        $sql .= "($bidId, $productId, $clientId, $bidAmount, '$comment', '$status');\n";

        $bidId++;
    }

    $sql .= "\n";

    // Subscriptions
    $sql .= "-- Subscriptions\n";
    for ($i = 12; $i <= 20; $i++) {
        $sql .= "INSERT INTO subscriptions (client_id, status) VALUES ($i, 'active');\n";
    }

    $sql .= "\n";

    // Notifications
    $sql .= "-- Notifications\n";
    $notifications = [
        [12, 'Welcome!', 'Thank you for joining our platform', 'info'],
        [13, 'Bid Received', 'Your bid has been received and is under review', 'success'],
        [14, 'New Product Alert', 'A new product matching your interest is available', 'info'],
        [15, 'Bid Approved', 'Congratulations! Your bid has been approved', 'success'],
        [2, 'New Bid', 'You have received a new bid on your product', 'info'],
        [3, 'Product Listed', 'Your product has been successfully listed', 'success']
    ];

    foreach ($notifications as $notif) {
        $sql .= "INSERT INTO notifications (user_id, title, message, type, is_read) VALUES\n";
        $sql .= "({$notif[0]}, '{$notif[1]}', '{$notif[2]}', '{$notif[3]}', " . (rand(0, 1) ? '1' : '0') . ");\n";
    }

    $sql .= "\nSET FOREIGN_KEY_CHECKS = 1;\n\n";
    $sql .= "-- ============================================================\n";
    $sql .= "-- Seed Data Complete!\n";
    $sql .= "-- Total: 10 Companies, 15 Clients, 50+ Products, 50 Bids\n";
    $sql .= "-- ============================================================\n";

    return $sql;
}

/**
 * Generate product data array
 */
function generateProductData()
{
    $products = [];

    // 2-wheelers (15 items)
    $bikes = [
        ['Honda Activa 6G', '2-wheeler', 'Deluxe BS6', 2022, 'HO' . rand(10000000, 99999999), 'Single Owner', rand(3000, 8000) . ' km', 72000],
        ['Bajaj Pulsar 150', '2-wheeler', 'Neon BS6', 2021, 'BA' . rand(10000000, 99999999), 'Student Owned', rand(10000, 20000) . ' km', 85000],
        ['Hero Splendor Plus', '2-wheeler', 'iSmart', 2023, 'HE' . rand(10000000, 99999999), 'First Owner', rand(2000, 5000) . ' km', 68000],
        ['Royal Enfield Classic', '2-wheeler', '350 Gunmetal', 2020, 'RE' . rand(10000000, 99999999), 'Enthusiast', rand(15000, 25000) . ' km', 155000],
        ['Yamaha R15 V3', '2-wheeler', 'V3 ABS', 2021, 'YA' . rand(10000000, 99999999), 'Racing Edition', rand(8000, 15000) . ' km', 145000],
        ['TVS Apache RTR 160', '2-wheeler', '4V BS6', 2022, 'TV' . rand(10000000, 99999999), 'Single Owner', rand(5000, 12000) . ' km', 110000],
        ['KTM Duke 390', '2-wheeler', 'BS6 ABS', 2020, 'KT' . rand(10000000, 99999999), 'Performance', rand(12000, 20000) . ' km', 225000],
        ['Suzuki Gixxer SF', '2-wheeler', 'MotoGP Edition', 2021, 'SU' . rand(10000000, 99999999), 'Well Maintained', rand(8000, 15000) . ' km', 125000],
        ['Honda CB Shine', '2-wheeler', 'SP BS6', 2022, 'HO' . rand(10000000, 99999999), 'Family Used', rand(4000, 10000) . ' km', 75000],
        ['Bajaj Avenger 220', '2-wheeler', 'Cruise', 2019, 'BA' . rand(10000000, 99999999), 'Cruiser Bike', rand(18000, 30000) . ' km', 95000],
        ['TVS Jupiter', '2-wheeler', 'ZX Disc', 2023, 'TV' . rand(10000000, 99999999), 'Lady Driven', rand(2000, 6000) . ' km', 70000],
        ['Hero Xtreme 160R', '2-wheeler', 'Stealth Edition', 2021, 'HE' . rand(10000000, 99999999), 'Sports Bike', rand(10000, 18000) . ' km', 105000],
        ['Yamaha FZ-S', '2-wheeler', 'V3 FI', 2020, 'YA' . rand(10000000, 99999999), 'Street Fighter', rand(15000, 25000) . ' km', 95000],
        ['Royal Enfield Bullet', '2-wheeler', '350 ES', 2018, 'RE' . rand(10000000, 99999999), 'Classic', rand(25000, 40000) . ' km', 125000],
        ['Honda Dio', '2-wheeler', 'BS6 DLX', 2022, 'HO' . rand(10000000, 99999999), 'College Girl', rand(3000, 8000) . ' km', 65000]
    ];

    foreach ($bikes as $bike) {
        $products[] = [
            'name' => $bike[0],
            'category' => $bike[1],
            'model' => $bike[2],
            'year' => $bike[3],
            'chassis' => $bike[4],
            'owner' => $bike[5],
            'duration' => $bike[6],
            'base_price' => $bike[7]
        ];
    }

    // 4-wheelers (20 items)
    $cars = [
        ['Maruti Swift Dzire', '4-wheeler', 'VXI Petrol', 2019, 'MA' . rand(10000000, 99999999), 'Single Owner', rand(40000, 60000) . ' km', 450000],
        ['Hyundai Creta', '4-wheeler', 'SX Diesel', 2021, 'HY' . rand(10000000, 99999999), 'First Owner', rand(25000, 35000) . ' km', 1250000],
        ['Honda City', '4-wheeler', 'ZX CVT', 2018, 'HO' . rand(10000000, 99999999), 'Doctor Owned', rand(50000, 70000) . ' km', 750000],
        ['Toyota Fortuner', '4-wheeler', '4x4 MT', 2016, 'TO' . rand(10000000, 99999999), 'Army Officer', rand(80000, 120000) . ' km', 1850000],
        ['Maruti Alto 800', '4-wheeler', 'LXI', 2020, 'MA' . rand(10000000, 99999999), 'Family Car', rand(30000, 50000) . ' km', 325000],
        ['Hyundai i20', '4-wheeler', 'Sportz', 2019, 'HY' . rand(10000000, 99999999), 'Well Maintained', rand(40000, 60000) . ' km', 685000],
        ['Tata Nexon', '4-wheeler', 'XZ Plus', 2021, 'TA' . rand(10000000, 99999999), 'Corporate', rand(25000, 40000) . ' km', 950000],
        ['Mahindra Scorpio', '4-wheeler', 'S11', 2020, 'MA' . rand(10000000, 99999999), 'SUV Lover', rand(45000, 65000) . ' km', 1150000],
        ['Ford EcoSport', '4-wheeler', 'Titanium', 2018, 'FO' . rand(10000000, 99999999), 'Single Owner', rand(55000, 75000) . ' km', 650000],
        ['Renault Kwid', '4-wheeler', 'RXT', 2021, 'RE' . rand(10000000, 99999999), 'City Drive', rand(20000, 35000) . ' km', 425000],
        ['Volkswagen Polo', '4-wheeler', 'Highline', 2017, 'VW' . rand(10000000, 99999999), 'Premium', rand(60000, 80000) . ' km', 525000],
        ['Toyota Innova Crysta', '4-wheeler', '2.4 GX', 2019, 'TO' . rand(10000000, 99999999), 'Taxi Board', rand(100000, 150000) . ' km', 1450000],
        ['Kia Seltos', '4-wheeler', 'HTX', 2021, 'KI' . rand(10000000, 99999999), 'Showroom Condition', rand(15000, 25000) . ' km', 1350000],
        ['Maruti Baleno', '4-wheeler', 'Delta AT', 2020, 'MA' . rand(10000000, 99999999), 'Automatic', rand(35000, 50000) . ' km', 725000],
        ['Honda Amaze', '4-wheeler', 'VX Diesel', 2018, 'HO' . rand(10000000, 99999999), 'Family Used', rand(60000, 80000) . ' km', 575000],
        ['Hyundai Verna', '4-wheeler', 'SX Turbo', 2020, 'HY' . rand(10000000, 99999999), 'Sedan Lover', rand(40000, 55000) . ' km', 950000],
        ['Tata Tiago', '4-wheeler', 'XZ', 2021, 'TA' . rand(10000000, 99999999), 'Hatchback', rand(25000, 40000) . ' km', 525000],
        ['Mahindra XUV500', '4-wheeler', 'W8', 2017, 'MA' . rand(10000000, 99999999), 'SUV', rand(75000, 100000) . ' km', 1025000],
        ['Renault Duster', '4-wheeler', 'RXZ AWD', 2016, 'RE' . rand(10000000, 99999999), 'Off-Road', rand(85000, 110000) . ' km', 625000],
        ['Nissan Magnite', '4-wheeler', 'XV Turbo', 2022, 'NI' . rand(10000000, 99999999), 'Brand New Condition', rand(10000, 20000) . ' km', 850000]
    ];

    foreach ($cars as $car) {
        $products[] = [
            'name' => $car[0],
            'category' => $car[1],
            'model' => $car[2],
            'year' => $car[3],
            'chassis' => $car[4],
            'owner' => $car[5],
            'duration' => $car[6],
            'base_price' => $car[7]
        ];
    }

    // Machinery (15 items)
    $machinery = [
        ['JCB 3DX Excavator', 'machinery', '3DX Super', 2020, 'JC' . rand(10000000, 99999999), 'Company Asset', rand(2000, 5000) . ' hrs', 1800000],
        ['Mahindra 575 DI Tractor', 'machinery', 'Bhoomiputra', 2019, 'MH' . rand(10000000, 99999999), 'Farmer Owned', rand(1500, 3000) . ' hrs', 475000],
        ['Swaraj 744 FE Tractor', 'machinery', '744 FE', 2021, 'SW' . rand(10000000, 99999999), 'Agriculture', rand(500, 1500) . ' hrs', 565000],
        ['John Deere 5050 D', 'machinery', '5050 D', 2020, 'JD' . rand(10000000, 99999999), 'Premium Brand', rand(1000, 2000) . ' hrs', 725000],
        ['New Holland 3630', 'machinery', 'TX Plus', 2021, 'NH' . rand(10000000, 99999999), 'High Power', rand(400, 1200) . ' hrs', 685000],
        ['Power Tiller VST', 'machinery', 'Shakti VT300', 2022, 'VS' . rand(10000000, 99999999), 'Small Farm', rand(300, 800) . ' hrs', 165000],
        ['Harvester Kartar', 'machinery', '4000 Series', 2019, 'KA' . rand(10000000, 99999999), 'Heavy Duty', rand(2500, 4000) . ' hrs', 1950000],
        ['Tata Hitachi Excavator', 'machinery', 'ZAXIS 33U', 2020, 'TH' . rand(10000000, 99999999), 'Construction', rand(3000, 5000) . ' hrs', 1650000],
        ['Mahindra EarthMaster', 'machinery', 'SX Loader', 2021, 'ME' . rand(10000000, 99999999), 'Loader', rand(1500, 2500) . ' hrs', 1485000],
        ['Industrial Sewing Machine', 'machinery', 'Juki DDL-8100', 2021, 'JK' . rand(10000000, 99999999), 'Factory', '2 years', 28000],
        ['Overlock Machine', 'machinery', 'Siruba 700D', 2020, 'SI' . rand(10000000, 99999999), 'Textile', '3 years', 38000],
        ['Rotavator', 'machinery', 'Land Force', 2020, 'LF' . rand(10000000, 99999999), 'Soil Preparation', rand(800, 1500) . ' hrs', 125000],
        ['Disc Harrow', 'machinery', 'Heavy Duty', 2019, 'DH' . rand(10000000, 99999999), 'Cultivator', rand(1000, 2000) . ' hrs', 95000],
        ['Seed Drill', 'machinery', 'Multi-Crop', 2021, 'SD' . rand(10000000, 99999999), 'Precision Seeding', rand(400, 1000) . ' hrs', 145000],
        ['Sprayer Pump', 'machinery', 'Honda GX160', 2022, 'SP' . rand(10000000, 99999999), 'Pesticide', rand(200, 600) . ' hrs', 45000]
    ];

    foreach ($machinery as $machine) {
        $products[] = [
            'name' => $machine[0],
            'category' => $machine[1],
            'model' => $machine[2],
            'year' => $machine[3],
            'chassis' => $machine[4],
            'owner' => $machine[5],
            'duration' => $machine[6],
            'base_price' => $machine[7]
        ];
    }

    return $products;
}

/**
 * Download product images from placeholder/stock APIs
 */
function downloadProductImages($imagesDir)
{
    $imageCount = 0;

    // Categories for image generation
    $categories = [
        '2-wheeler' => ['motorcycle', 'scooter', 'bike'],
        '4-wheeler' => ['car', 'suv', 'vehicle'],
        'machinery' => ['tractor', 'excavator', 'equipment']
    ];

    // Generate 50 placeholder images
    for ($i = 1; $i <= 50; $i++) {
        $category = $i <= 15 ? '2-wheeler' : ($i <= 35 ? '4-wheeler' : 'machinery');
        $keyword = $categories[$category][array_rand($categories[$category])];

        // Use placeholder image service (picsum.photos)
        $width = 800;
        $height = 600;

        // Try to download image
        $imageUrl = "https://picsum.photos/$width/$height?random=$i";
        $imagePath = $imagesDir . "product_$i.jpg";

        try {
            $imageData = @file_get_contents($imageUrl);
            if ($imageData !== false) {
                file_put_contents($imagePath, $imageData);
                $imageCount++;
            }
        } catch (Exception $e) {
            // If download fails, create a simple placeholder
            createPlaceholderImage($imagePath, "Product $i", $width, $height);
            $imageCount++;
        }

        // Small delay to avoid rate limiting
        usleep(100000); // 0.1 second
    }

    return $imageCount;
}

/**
 * Create placeholder image if download fails
 */
function createPlaceholderImage($path, $text, $width, $height)
{
    $image = imagecreatetruecolor($width, $height);

    // Colors
    $bgColor = imagecolorallocate($image, 240, 240, 240);
    $textColor = imagecolorallocate($image, 100, 100, 100);
    $borderColor = imagecolorallocate($image, 200, 200, 200);

    // Fill background
    imagefilledrectangle($image, 0, 0, $width, $height, $bgColor);

    // Draw border
    imagerectangle($image, 0, 0, $width - 1, $height - 1, $borderColor);

    // Add text
    $fontSize = 5;
    $textWidth = imagefontwidth($fontSize) * strlen($text);
    $textHeight = imagefontheight($fontSize);
    $x = ($width - $textWidth) / 2;
    $y = ($height - $textHeight) / 2;

    imagestring($image, $fontSize, $x, $y, $text, $textColor);

    // Save image
    imagejpeg($image, $path, 90);
    imagedestroy($image);
}

/**
 * Create zip package with SQL and images
 */
function createSeedPackage($downloadsDir, $sqlFile, $imagesDir)
{
    if (!class_exists('ZipArchive')) {
        return null; // ZipArchive not available
    }

    $zipFile = $downloadsDir . 'seed_package_' . date('Y-m-d_His') . '.zip';
    $zip = new ZipArchive();

    if ($zip->open($zipFile, ZipArchive::CREATE) !== true) {
        return null;
    }

    // Add SQL file
    $zip->addFile($sqlFile, 'seed_data.sql');

    // Add images
    $images = glob($imagesDir . '*.jpg');
    foreach ($images as $image) {
        $zip->addFile($image, 'product_images/' . basename($image));
    }

    // Add README
    $readme = "BID FOR USED PRODUCT - Seed Data Package\n\n";
    $readme .= "Generated: " . date('Y-m-d H:i:s') . "\n\n";
    $readme .= "Contents:\n";
    $readme .= "1. seed_data.sql - Complete database seed data\n";
    $readme .= "2. product_images/ - Product placeholder images\n\n";
    $readme .= "Installation:\n";
    $readme .= "1. Import seed_data.sql into your database\n";
    $readme .= "2. Copy product images to uploads/products/ directory\n\n";
    $readme .= "Default Credentials:\n";
    $readme .= "Admin: admin@example.com / admin123\n";
    $readme .= "Company: (any company email) / company123\n";
    $readme .= "Client: (any client email) / client123\n";


    $zip->addFromString('README.txt', $readme);

    $zip->close();

    return $zipFile;
}
