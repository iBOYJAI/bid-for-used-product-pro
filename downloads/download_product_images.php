<?php

/**
 * STRICT PRODUCT IMAGE DOWNLOADER
 * Focus: Cars, Bikes, Machinery ONLY.
 * No SVGs. No complex fallbacks to nature.
 */

// Disable time limit for long download process
set_time_limit(0);

// API Keys
define('PEXELS_KEY', 'Splj8ikSH1WsNvO8ffKaBzSeLCwOHgt9OBWu9O4n2ypepEFncKOyMcri');
define('UNSPLASH_KEY', 'VJufhHeJ4bEFznVal6qcMnKhL-Oc_dgRBrFCdnIXh-s');
define('PIXABAY_KEY', '47724058-69a1d742c3ed0a989b3f7f83d');

// Target directory
$baseDir = __DIR__ . '/product_images/';
if (!is_dir($baseDir)) {
    mkdir($baseDir, 0777, true);
}

// Product List
$products = [
    // Cars
    1 => 'Maruti Swift Dzire car',
    2 => 'Hyundai Creta car',
    3 => 'Honda City car',
    4 => 'Toyota Fortuner car',
    5 => 'Mahindra XUV500 car',
    6 => 'Tata Nexon car',
    7 => 'Toyota Innova car',
    8 => 'Kia Seltos car',
    9 => 'Ford EcoSport car',
    10 => 'Mahindra Scorpio car',
    11 => 'Renault Duster car',
    12 => 'Volkswagen Polo car',
    13 => 'Skoda Rapid car',
    14 => 'Nissan Kicks car',
    15 => 'MG Hector car',
    16 => 'BMW 3 Series car',
    17 => 'Audi Q3 car',
    18 => 'Mercedes C-Class car',
    19 => 'Honda WR-V car',
    20 => 'Tata Tiago car',
    21 => 'Volkswagen Vento car',
    22 => 'Hyundai Venue car',
    23 => 'Jeep Compass car',
    24 => 'Mahindra Thar car',
    25 => 'Maruti Ertiga car',
    26 => 'Ford Endeavour car',
    27 => 'Honda Amaze car',
    28 => 'Renault Kwid car',
    29 => 'Hyundai i20 car',
    30 => 'Tata Harrier car',

    // Bikes
    31 => 'Royal Enfield Classic motorcycle',
    32 => 'Yamaha R15 motorcycle',
    33 => 'KTM Duke motorcycle',
    34 => 'Honda CB Shine motorcycle',
    35 => 'TVS Apache motorcycle',
    36 => 'Bajaj Pulsar motorcycle',
    37 => 'Hero Splendor motorcycle',
    38 => 'Suzuki Gixxer motorcycle',
    39 => 'Honda Activa scooter',
    40 => 'TVS Jupiter scooter',
    41 => 'Bajaj Avenger motorcycle',
    42 => 'Royal Enfield Himalayan motorcycle',
    43 => 'Yamaha FZ motorcycle',
    44 => 'KTM RC motorcycle',
    45 => 'Honda Dio scooter',
    46 => 'Hero Xtreme motorcycle',
    47 => 'Suzuki Access scooter',
    48 => 'Bajaj Dominar motorcycle',
    49 => 'TVS XL100 moped',
    50 => 'Royal Enfield Thunderbird motorcycle',

    // Machinery
    51 => 'Mahindra Tractor',
    52 => 'Swaraj Tractor',
    53 => 'JCB Excavator',
    54 => 'Hitachi Excavator',
    55 => 'John Deere Tractor',
    56 => 'New Holland Tractor',
    57 => 'Komatsu Excavator',
    58 => 'Power Tiller farm',
    59 => 'Combine Harvester',
    60 => 'Industrial Sewing Machine',
    61 => 'Overlock Machine',
    62 => 'Industrial Generator',
    63 => 'Concrete Mixer truck',
    64 => 'Wheel Loader',
    65 => 'Rotavator farm'
];

echo "------------------------------------------------\n";
echo "   STARTING STRICT PRODUCT IMAGE DOWNLOAD\n";
echo "------------------------------------------------\n";

foreach ($products as $id => $searchTerm) {
    echo "\nProcessing Product ID $id: $searchTerm\n";

    $productDir = $baseDir . "product_$id/";
    if (!is_dir($productDir)) {
        mkdir($productDir, 0777, true);
    }

    // We need 10 images
    $imagesFound = 0;

    // 1. Search Unsplash (High quality)
    if ($imagesFound < 10) {
        $imagesFound += downloadFromUnsplash($searchTerm, $productDir, 10 - $imagesFound, $imagesFound);
    }

    // 2. Search Pexels
    if ($imagesFound < 10) {
        $imagesFound += downloadFromPexels($searchTerm, $productDir, 10 - $imagesFound, $imagesFound);
    }

    // 3. Search Pixabay
    if ($imagesFound < 10) {
        $imagesFound += downloadFromPixabay($searchTerm, $productDir, 10 - $imagesFound, $imagesFound);
    }

    // 4. Fallback: Split words for broader search if strict valid failed
    // e.g., "Maruti Swift Dzire" -> "Swift car" or "Suzuki Swift"
    if ($imagesFound < 10) {
        $fallbackTerm = simplifySearchTerm($searchTerm);
        if ($fallbackTerm !== $searchTerm) {
            echo "  Using fallback search: $fallbackTerm\n";
            if ($imagesFound < 10) $imagesFound += downloadFromUnsplash($fallbackTerm, $productDir, 10 - $imagesFound, $imagesFound);
            if ($imagesFound < 10) $imagesFound += downloadFromPexels($fallbackTerm, $productDir, 10 - $imagesFound, $imagesFound);
            if ($imagesFound < 10) $imagesFound += downloadFromPixabay($fallbackTerm, $productDir, 10 - $imagesFound, $imagesFound);
        }
    }

    echo "  Total downloaded: $imagesFound/10\n";

    // Clean up empty files if any
    $files = glob($productDir . "*");
    foreach ($files as $file) {
        if (filesize($file) < 1000) { // Delete very small files (errors)
            unlink($file);
        }
    }
}

echo "\n------------------------------------------------\n";
echo "   DOWNLOAD COMPLETE\n";
echo "------------------------------------------------\n";


// --------------------------------------------------------------------------
// Helper Functions
// --------------------------------------------------------------------------

function downloadFromUnsplash($query, $dir, $limit, $startIndex)
{
    $count = 0;
    $url = "https://api.unsplash.com/search/photos?client_id=" . UNSPLASH_KEY . "&query=" . urlencode($query) . "&per_page=" . ($limit + 5) . "&orientation=landscape";

    $response = makeApiRequest($url);
    $data = json_decode($response, true);

    if (isset($data['results']) && is_array($data['results'])) {
        foreach ($data['results'] as $img) {
            if ($count >= $limit) break;

            // Filter out obviously bad keywords if possible, though strict query helps
            if (isUnwanted($img['description'] ?? '') || isUnwanted($img['alt_description'] ?? '')) continue;

            $imgUrl = $img['urls']['regular'];
            $targetFile = $dir . "image_" . ($startIndex + $count + 1) . ".jpg";

            if (downloadFile($imgUrl, $targetFile)) {
                echo "  [Unsplash] Downloaded image " . ($startIndex + $count + 1) . "\n";
                $count++;
            }
        }
    }
    return $count;
}

function downloadFromPexels($query, $dir, $limit, $startIndex)
{
    $count = 0;
    $url = "https://api.pexels.com/v1/search?query=" . urlencode($query) . "&per_page=" . ($limit + 5) . "&orientation=landscape";

    $headers = ["Authorization: " . PEXELS_KEY];
    $response = makeApiRequest($url, $headers);
    $data = json_decode($response, true);

    if (isset($data['photos']) && is_array($data['photos'])) {
        foreach ($data['photos'] as $img) {
            if ($count >= $limit) break;

            if (isUnwanted($img['alt'] ?? '')) continue;

            $imgUrl = $img['src']['large'];
            $targetFile = $dir . "image_" . ($startIndex + $count + 1) . ".jpg";

            if (downloadFile($imgUrl, $targetFile)) {
                echo "  [Pexels] Downloaded image " . ($startIndex + $count + 1) . "\n";
                $count++;
            }
        }
    }
    return $count;
}

function downloadFromPixabay($query, $dir, $limit, $startIndex)
{
    $count = 0;
    $url = "https://pixabay.com/api/?key=" . PIXABAY_KEY . "&q=" . urlencode($query) . "&image_type=photo&per_page=" . ($limit + 5) . "&orientation=horizontal";

    $response = makeApiRequest($url);
    $data = json_decode($response, true);

    if (isset($data['hits']) && is_array($data['hits'])) {
        foreach ($data['hits'] as $img) {
            if ($count >= $limit) break;

            if (isUnwanted($img['tags'] ?? '')) continue;

            $imgUrl = $img['largeImageURL'];
            $targetFile = $dir . "image_" . ($startIndex + $count + 1) . ".jpg";

            if (downloadFile($imgUrl, $targetFile)) {
                echo "  [Pixabay] Downloaded image " . ($startIndex + $count + 1) . "\n";
                $count++;
            }
        }
    }
    return $count;
}

function makeApiRequest($url, $headers = [])
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    if (!empty($headers)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

function downloadFile($url, $path)
{
    $ch = curl_init($url);
    $fp = fopen($path, 'wb');
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    fclose($fp);

    if ($httpCode == 200 && filesize($path) > 1000) {
        return true;
    } else {
        @unlink($path);
        return false;
    }
}

// Simple filter to reduce obviously bad matches if the API returns them
function isUnwanted($text)
{
    $badWords = ['person', 'people', ' man', ' woman', 'child', 'girl', 'boy', 'face', 'portrait', 'family', 'couple', 'crowd', 'bird', 'dog', 'cat', 'animal', 'flower', 'tree ', 'forest', 'food', 'meal', 'building interior', 'room'];
    $text = strtolower($text);
    foreach ($badWords as $word) {
        if (strpos($text, $word) !== false) return true;
    }
    return false;
}

function simplifySearchTerm($term)
{
    // E.g., "Maruti Swift Dzire car" -> "Swift car" or "Suzuki Swift"
    // This is a basic fallback logic.
    if (strpos($term, 'Maruti') !== false) return str_replace('Maruti', 'Suzuki', $term);
    if (strpos($term, 'Mahindra') !== false) return 'SUV car'; // Mahindra specific might fail
    if (strpos($term, 'Tata') !== false) return 'SUV car';

    // Remove specific model numbers if they are too complex
    // Just return the last two words usually works for broadly correct images
    $parts = explode(' ', $term);
    if (count($parts) > 2) {
        return end($parts) . ' ' . prev($parts); // "car Dzire" -> "Dzire car"
    }

    return $term;
}
