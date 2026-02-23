<?php
/**
 * DEPLOY PATCH — Download semua file dari GitHub ke hosting
 * + Fix database room_types trailing spaces
 * Upload ke: public_html/narayanakarimunjawa.com/
 * Buka: https://narayanakarimunjawa.com/patch.php
 */
header('Content-Type: text/html; charset=utf-8');
echo '<h2>Deploying Narayana...</h2>';

// Fix trailing spaces in room_types table
try {
    $dbName = 'adfb2574_narayana_hotel';
    $dbUser = 'adfb2574_adfsystem';
    $dbPass = '@Nnoc2025';
    $pdo = new PDO("mysql:host=localhost;dbname=$dbName", $dbUser, $dbPass);
    $pdo->exec("UPDATE room_types SET type_name = TRIM(type_name)");
    echo "<p style='color:green'>✅ Database: room_types type_name trimmed</p>";
} catch (Exception $e) {
    echo "<p style='color:orange'>⚠️ DB fix skipped: " . $e->getMessage() . "</p>";
}

$GITHUB = 'https://raw.githubusercontent.com/marcellapratiknyo-rgb/web/main';

// Semua file yang perlu di-deploy (GitHub path => hosting path)
$files = [
    'public/index.php'                  => 'index.php',
    'public/rooms.php'                  => 'rooms.php',
    'public/booking.php'                => 'booking.php',
    'public/contact.php'                => 'contact.php',
    'public/confirmation.php'           => 'confirmation.php',
    'public/destinations.php'           => 'destinations.php',
    'public/includes/header.php'        => 'includes/header.php',
    'public/includes/footer.php'        => 'includes/footer.php',
    'public/assets/css/style.css'       => 'assets/css/style.css',
    'public/api/create-booking.php'     => 'api/create-booking.php',
    'config/config.php'                 => 'config/config.php',
];

$ok = 0; $fail = 0;
foreach ($files as $src => $dest) {
    $ch = curl_init("$GITHUB/$src");
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_USERAGENT => 'Patch/1.0',
    ]);
    $content = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($code === 200 && $content) {
        $path = __DIR__ . '/' . $dest;
        @mkdir(dirname($path), 0755, true);
        file_put_contents($path, $content);
        echo "<p style='color:green'>✅ $dest (" . strlen($content) . " bytes)</p>";
        $ok++;
    } else {
        echo "<p style='color:red'>❌ $dest (HTTP $code)</p>";
        $fail++;
    }
}

echo "<h3>✅ Done! $ok deployed, $fail failed</h3>";
echo '<p><a href="/">← Homepage</a> | <a href="/rooms.php">Rooms</a></p>';
@unlink(__FILE__);
