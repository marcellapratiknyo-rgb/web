<?php
/**
 * DUAL DATABASE TEST
 * Test koneksi ke 2 database sekaligus
 */

require_once __DIR__ . '/config/config.php';

echo "<h1>🔍 Dual Database Connection Test</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; padding: 20px; background: #f5f5f5; }
    .success { color: green; font-weight: bold; }
    .error { color: red; font-weight: bold; }
    .info { background: #e3f2fd; padding: 15px; border-left: 4px solid #2196F3; margin: 10px 0; }
    table { width: 100%; border-collapse: collapse; margin: 15px 0; background: white; }
    th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
    th { background: #2196F3; color: white; }
    tr:hover { background: #f5f5f5; }
    h2 { color: #333; margin-top: 30px; border-bottom: 2px solid #2196F3; padding-bottom: 10px; }
</style>";

// Test Database SISTEM
echo "<h2>📊 Database 1: SISTEM ADF (adf_narayana_hotel)</h2>";
echo "<div class='info'><strong>Fungsi:</strong> Sumber data master (room types, harga, occupancy) - READ ONLY</div>";

try {
    // Test connection
    $test = $pdo->query("SELECT DATABASE() as db_name")->fetch();
    echo "<p class='success'>✓ Koneksi berhasil ke: <strong>" . $test['db_name'] . "</strong></p>";
    
    // Count room types
    $room_types_count = $pdo->query("SELECT COUNT(*) as count FROM room_types")->fetch();
    echo "<p>Total Room Types: <strong>" . $room_types_count['count'] . "</strong></p>";
    
    // Show room types
    echo "<h3>Room Types dari Sistem:</h3>";
    $room_types = $pdo->query("SELECT id, type_name, base_price, max_guests FROM room_types LIMIT 5")->fetchAll();
    
    if (count($room_types) > 0) {
        echo "<table>";
        echo "<tr><th>ID</th><th>Room Type</th><th>Harga/Malam</th><th>Max Tamu</th></tr>";
        foreach ($room_types as $room) {
            echo "<tr>";
            echo "<td>" . $room['id'] . "</td>";
            echo "<td>" . $room['type_name'] . "</td>";
            echo "<td>" . formatCurrency($room['base_price']) . "</td>";
            echo "<td>" . $room['max_guests'] . " orang</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p class='error'>⚠ Belum ada data room_types di database sistem</p>";
    }
    
    // Count bookings
    $bookings_count = $pdo->query("SELECT COUNT(*) as count FROM bookings")->fetch();
    echo "<p>Total Bookings di Sistem: <strong>" . $bookings_count['count'] . "</strong></p>";
    
} catch (PDOException $e) {
    echo "<p class='error'>✗ Error koneksi database SISTEM: " . $e->getMessage() . "</p>";
}

// Test Database WEBSITE
echo "<h2>🌐 Database 2: WEBSITE BOOKING (adf_web_narayana)</h2>";
echo "<div class='info'><strong>Fungsi:</strong> Simpan transaksi website (bookings, payments, guests) - READ WRITE</div>";

try {
    // Test connection
    $test_web = $pdo_web->query("SELECT DATABASE() as db_name")->fetch();
    echo "<p class='success'>✓ Koneksi berhasil ke: <strong>" . $test_web['db_name'] . "</strong></p>";
    
    // Show tables
    $tables = $pdo_web->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "<p>Total Tables: <strong>" . count($tables) . "</strong></p>";
    echo "<p>Tables: " . implode(', ', $tables) . "</p>";
    
    // Count data
    $stats = [
        'bookings' => $pdo_web->query("SELECT COUNT(*) as c FROM bookings")->fetch()['c'],
        'guests' => $pdo_web->query("SELECT COUNT(*) as c FROM guests")->fetch()['c'],
        'payments' => $pdo_web->query("SELECT COUNT(*) as c FROM payments")->fetch()['c'],
        'reviews' => $pdo_web->query("SELECT COUNT(*) as c FROM reviews")->fetch()['c'],
    ];
    
    echo "<h3>Data di Database Website:</h3>";
    echo "<table>";
    echo "<tr><th>Tabel</th><th>Jumlah Record</th></tr>";
    foreach ($stats as $table => $count) {
        echo "<tr><td>" . ucfirst($table) . "</td><td><strong>" . $count . "</strong></td></tr>";
    }
    echo "</table>";
    
    // Show recent bookings
    $recent_bookings = $pdo_web->query("
        SELECT b.*, g.full_name, g.email 
        FROM bookings b 
        LEFT JOIN guests g ON b.guest_id = g.id 
        ORDER BY b.created_at DESC 
        LIMIT 5
    ")->fetchAll();
    
    if (count($recent_bookings) > 0) {
        echo "<h3>Booking Terbaru:</h3>";
        echo "<table>";
        echo "<tr><th>ID</th><th>Guest</th><th>Check-in</th><th>Check-out</th><th>Status</th><th>Total</th></tr>";
        foreach ($recent_bookings as $booking) {
            echo "<tr>";
            echo "<td>#" . $booking['id'] . "</td>";
            echo "<td>" . ($booking['full_name'] ?? 'N/A') . "</td>";
            echo "<td>" . formatDate($booking['check_in']) . "</td>";
            echo "<td>" . formatDate($booking['check_out']) . "</td>";
            echo "<td>" . $booking['status'] . "</td>";
            echo "<td>" . formatCurrency($booking['total_price']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>Belum ada booking dari website</p>";
    }
    
} catch (PDOException $e) {
    echo "<p class='error'>✗ Error koneksi database WEBSITE: " . $e->getMessage() . "</p>";
}

// Summary
echo "<h2>📋 Kesimpulan</h2>";
echo "<div class='info'>";
echo "<p><strong>✓ Dual Database Setup Aktif!</strong></p>";
echo "<p>Website sekarang bisa:</p>";
echo "<ul>";
echo "<li>📖 AMBIL data master (room types, harga, occupancy) dari <strong>database SISTEM</strong></li>";
echo "<li>💾 SIMPAN booking customer dari website ke <strong>database WEBSITE</strong></li>";
echo "</ul>";
echo "<p>Dengan arsitektur ini, sistem ADF dan website booking <strong>terpisah</strong> tapi tetap <strong>sinkron</strong>.</p>";
echo "</div>";

echo "<h2>📚 Cara Menggunakan</h2>";
echo "<div class='info'>";
echo "<pre style='background: #263238; color: #aed581; padding: 15px; border-radius: 5px; overflow-x: auto;'>";
echo "// Ambil room types dari SISTEM\n";
echo "\$room_types = dbFetchAll(\"SELECT * FROM room_types WHERE is_active = 1\");\n\n";
echo "// Simpan booking ke WEBSITE\n";
echo "\$booking_id = dbWebInsert('bookings', [\n";
echo "    'guest_id' => \$guest_id,\n";
echo "    'room_type_id' => \$room_type_id,\n";
echo "    'check_in' => \$check_in,\n";
echo "    'check_out' => \$check_out,\n";
echo "    'total_price' => \$total_price\n";
echo "]);\n";
echo "</pre>";
echo "<p>📖 Baca lengkap di: <a href='DUAL-DATABASE-GUIDE.md' target='_blank'>DUAL-DATABASE-GUIDE.md</a></p>";
echo "</div>";
?>
