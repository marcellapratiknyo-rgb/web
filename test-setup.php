<?php
/**
 * Setup Test Script - Verify all components are working
 */

require_once dirname(__DIR__) . '/narayanakarimunjawa/config/config.php';

echo "=== NARAYANA KARIMUNJAWA - SETUP TEST ===\n\n";

// 1. Test Database Connection
echo "1. Database Connection: ";
try {
    $testQuery = $pdo->query("SELECT COUNT(*) as count FROM room_types");
    $result = $testQuery->fetch(PDO::FETCH_ASSOC);
    echo "✓ OK (Connected to " . DB_NAME . ")\n";
} catch (PDOException $e) {
    echo "✗ FAILED: " . $e->getMessage() . "\n";
}

// 2. Test Room Types Table
echo "\n2. Room Types Data:\n";
try {
    $query = "SELECT id, type_name, base_price FROM room_types";
    $stmt = $pdo->query($query);
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($rooms) > 0) {
        echo "   ✓ " . count($rooms) . " rooms found:\n";
        foreach ($rooms as $room) {
            echo "     - {$room['type_name']} (Rp " . number_format($room['base_price'], 0) . ")\n";
        }
    } else {
        echo "   ✗ No room types found\n";
    }
} catch (Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
}

// 3. Test Settings Table
echo "\n3. Database Settings:\n";
try {
    $query = "SELECT COUNT(*) as count FROM settings";
    $result = $pdo->query($query)->fetch(PDO::FETCH_ASSOC);
    echo "   ✓ {$result['count']} settings configured\n";
} catch (Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
}

// 4. Test File Paths
echo "\n4. File Path Verification:\n";
$files = [
    'Config File' => dirname(__DIR__) . '/narayanakarimunjawa/config/config.php',
    'Header Template' => dirname(__DIR__) . '/narayanakarimunjawa/public/includes/header.php',
    'Footer Template' => dirname(__DIR__) . '/narayanakarimunjawa/public/includes/footer.php',
];

foreach ($files as $name => $path) {
    if (file_exists($path)) {
        echo "   ✓ {$name}\n";
    } else {
        echo "   ✗ {$name} NOT FOUND: {$path}\n";
    }
}

// 5. Test Constants
echo "\n5. Configuration Constants:\n";
echo "   Site Name: " . SITE_NAME . "\n";
echo "   Site URL: " . SITE_URL . "\n";
echo "   Base URL: " . BASE_URL . "\n";
echo "   Debug Mode: " . (DEBUG_MODE ? "ON" : "OFF") . "\n";

echo "\n=== SETUP VERIFICATION COMPLETE ===\n";
