<?php
/**
 * DEPLOY PATCH — Restore website dari GitHub (root repo, BUKAN adf_sytem)
 * Sama persis seperti .cpanel.yml deployment
 * Upload ke: public_html/narayanakarimunjawa.com/
 * Buka: https://narayanakarimunjawa.com/patch.php
 */
header('Content-Type: text/html; charset=utf-8');
echo '<h2>🚀 Restoring Narayana Website...</h2>';
echo '<p style="color:#888">Source: root repo (same as .cpanel.yml)</p>';

// GitHub raw URLs — TWO separate repos
$GITHUB_WEB = 'https://raw.githubusercontent.com/marcellapratiknyo-rgb/web/main';
$GITHUB_ADF = 'https://raw.githubusercontent.com/marcellapratiknyo-rgb/adf_sytem/main';

// ===== STEP 1: Website files (from WEB repo public/) =====
echo '<h3>📦 Step 1: Website Files</h3>';

$websiteFiles = [
    // Source (GitHub path)                    => Destination (relative to narayanakarimunjawa.com/)
    'public/index.php'                         => 'index.php',
    'public/rooms.php'                         => 'rooms.php',
    'public/booking.php'                       => 'booking.php',
    'public/contact.php'                       => 'contact.php',
    'public/confirmation.php'                  => 'confirmation.php',
    'public/destinations.php'                  => 'destinations.php',
    'public/includes/header.php'               => 'includes/header.php',
    'public/includes/footer.php'               => 'includes/footer.php',
    'public/assets/css/style.css'              => 'assets/css/style.css',
    'public/api/create-booking.php'            => 'api/create-booking.php',
    'config/config.php'                        => 'config/config.php',
];

$ok = 0; $fail = 0;
foreach ($websiteFiles as $src => $dest) {
    $url = "$GITHUB_WEB/$src";
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_USERAGENT => 'Patch/3.0',
    ]);
    $content = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($code === 200 && $content) {
        $path = __DIR__ . '/' . $dest;
        @mkdir(dirname($path), 0755, true);
        file_put_contents($path, $content);
        $sizeKB = round(strlen($content)/1024, 1);
        echo "<p style='color:green'>✅ $dest ({$sizeKB} KB)</p>";
        $ok++;
    } else {
        echo "<p style='color:red'>❌ $dest (HTTP $code)</p>";
        $fail++;
    }
}

// ===== STEP 2: Developer panel (from adf_sytem/) =====
echo '<h3>🔧 Step 2: Developer Panel</h3>';

$devFiles = [
    'developer/web-settings.php' => '/home/adfb2574/public_html/adf_system/developer/web-settings.php',
];

foreach ($devFiles as $src => $destAbs) {
    $url = "$GITHUB_ADF/$src";
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_USERAGENT => 'Patch/3.0',
    ]);
    $content = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($code === 200 && $content) {
        @mkdir(dirname($destAbs), 0755, true);
        file_put_contents($destAbs, $content);
        $sizeKB = round(strlen($content)/1024, 1);
        echo "<p style='color:green'>✅ web-settings.php ({$sizeKB} KB) → adf_system/developer/</p>";
        $ok++;
    } else {
        echo "<p style='color:red'>❌ web-settings.php (HTTP $code)</p>";
        $fail++;
    }
}

// ===== STEP 3: Ensure directories =====
echo '<h3>📁 Step 3: Directories</h3>';
$dirs = ['includes', 'config', 'assets/css', 'assets/images', 'assets/js', 'api', 'logs', 'uploads', 'uploads/hero', 'uploads/logo', 'uploads/favicon', 'uploads/rooms', 'uploads/destinations'];
foreach ($dirs as $d) {
    $fullDir = __DIR__ . '/' . $d;
    if (!is_dir($fullDir)) {
        mkdir($fullDir, 0755, true);
        echo "<p style='color:#64b5f6'>📁 Created: $d/</p>";
    }
}

echo "<hr>";
echo "<h3 style='color:" . ($fail > 0 ? 'orange' : 'green') . "'>Done! $ok deployed, $fail failed</h3>";
echo '<p>';
echo '<a href="/" style="margin-right:15px">🌐 Homepage</a>';
echo '<a href="/rooms.php" style="margin-right:15px">🛏️ Rooms</a>';
echo '<a href="https://adfsystem.online/adf_system/developer/web-settings.php">🔧 Developer</a>';
echo '</p>';
echo '<p style="color:#999;font-size:12px">Patch v3 deployed at: ' . date('Y-m-d H:i:s') . '</p>';
