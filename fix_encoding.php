<?php
// Diagnose exact bytes in the broken sequences
$file = __DIR__ . '/COMPLETE_PROJECT_REPORT.md';
$content = file_get_contents($file);

// Find the first occurrence of the broken text and show its bytes
header('Content-Type: text/plain; charset=utf-8');

// Find position of first broken sequence
$pos = strpos($content, '2025');
if ($pos !== false) {
    // Show 30 bytes around it
    $chunk = substr($content, $pos, 30);
    echo "Sample bytes around '2025':\n";
    for ($i = 0; $i < strlen($chunk); $i++) {
        $byte = ord($chunk[$i]);
        $char = ($byte >= 32 && $byte < 128) ? $chunk[$i] : '?';
        echo sprintf("0x%02X(%s) ", $byte, $char);
    }
    echo "\n\n";
}

// Now try the correct fix — read as Latin-1, let PHP see raw bytes
// The file seems to have been saved with UTF-8 characters but when we read it
// PHP sees the raw bytes. Let's convert from Windows-1252 to UTF-8
echo "File size: " . filesize($file) . " bytes\n";
echo "First 100 bytes:\n";
$raw = substr($content, 0, 100);
for ($i = 0; $i < strlen($raw); $i++) {
    $byte = ord($raw[$i]);
    $char = ($byte >= 32 && $byte < 128) ? $raw[$i] : '.';
    echo sprintf("%02X", $byte) . " ";
}
echo "\n\n";

// Try direct string replacement using the literal mojibake strings
// as they appear in the UTF-8 encoded file
$mojibake_test = substr($content, strpos($content, '2025'), 10);
echo "Raw hex of '2025...' sequence:\n";
for ($i = 0; $i < strlen($mojibake_test); $i++) {
    echo sprintf("%02X ", ord($mojibake_test[$i]));
}
echo "\n";
