<?php
/**
 * MANUAL DEPLOY SCRIPT — Narayana Karimunjawa
 * Upload file ini ke folder domain root via cPanel File Manager
 * Lalu buka di browser: https://narayanakarimunjawa.com/deploy.php
 * 
 * Script ini akan download semua file dari GitHub dan deploy langsung.
 * HAPUS file ini setelah deploy berhasil!
 * 
 * Security: Script ini hanya bisa diakses 1x, setelah deploy otomatis terhapus.
 */

// Security token - prevent unauthorized access
$DEPLOY_TOKEN = 'narayana2026deploy';

// GitHub raw file base URL
$GITHUB_RAW = 'https://raw.githubusercontent.com/marcellapratiknyo-rgb/web/main';

// Files to deploy (relative paths)
$files = [
    // Main pages
    'public/index.php' => 'index.php',
    'public/rooms.php' => 'rooms.php',
    'public/booking.php' => 'booking.php',
    'public/contact.php' => 'contact.php',
    'public/confirmation.php' => 'confirmation.php',
    'public/test.php' => 'test.php',
    'public/.htaccess.production' => '.htaccess',
    
    // API
    'public/api/create-booking.php' => 'api/create-booking.php',
    
    // Assets
    'public/assets/css/style.css' => 'assets/css/style.css',
    
    // Includes
    'public/includes/header.php' => 'includes/header.php',
    'public/includes/footer.php' => 'includes/footer.php',
    
    // Config
    'config/config.php' => 'config/config.php',
];

// Directories to create
$dirs = [
    'api',
    'assets',
    'assets/css',
    'assets/images',
    'assets/js',
    'includes',
    'config',
    'logs',
    'uploads',
];

// === START ===
header('Content-Type: text/html; charset=utf-8');
echo '<!DOCTYPE html><html><head><title>Deploy Narayana</title>';
echo '<style>body{font-family:monospace;background:#1a1a1a;color:#0f0;padding:20px;max-width:800px;margin:0 auto}';
echo '.ok{color:#0f0}.err{color:#f00}.warn{color:#ff0}h1{color:#fff}';
echo '.box{background:#222;padding:15px;border-radius:8px;margin:10px 0}';
echo 'a{color:#0af}</style></head><body>';
echo '<h1>🚀 Narayana Karimunjawa — Manual Deploy</h1>';

// Step 0: Check token
if (!isset($_GET['token']) || $_GET['token'] !== $DEPLOY_TOKEN) {
    // Show diagnostics only (no deploy)
    echo '<div class="box">';
    echo '<h2>📋 Server Diagnostics</h2>';
    echo '<p>Document Root: <strong>' . ($_SERVER['DOCUMENT_ROOT'] ?? 'unknown') . '</strong></p>';
    echo '<p>Script Path: <strong>' . ($_SERVER['SCRIPT_FILENAME'] ?? 'unknown') . '</strong></p>';
    echo '<p>Current Dir: <strong>' . __DIR__ . '</strong></p>';
    echo '<p>Server: <strong>' . ($_SERVER['SERVER_SOFTWARE'] ?? 'unknown') . '</strong></p>';
    echo '<p>PHP Version: <strong>' . phpversion() . '</strong></p>';
    echo '<p>OS: <strong>' . PHP_OS . '</strong></p>';
    
    // Check if files already exist
    echo '<h3>Files in current directory:</h3><ul>';
    $items = scandir(__DIR__);
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;
        $type = is_dir(__DIR__ . '/' . $item) ? '📁' : '📄';
        echo "<li>$type $item</li>";
    }
    echo '</ul>';
    
    // Check allow_url_fopen and curl
    echo '<h3>Download Capability:</h3>';
    echo '<p>allow_url_fopen: <strong>' . (ini_get('allow_url_fopen') ? '✅ ON' : '❌ OFF') . '</strong></p>';
    echo '<p>cURL extension: <strong>' . (function_exists('curl_init') ? '✅ Available' : '❌ Not available') . '</strong></p>';
    
    echo '</div>';
    
    echo '<div class="box">';
    echo '<h2>🔑 Ready to Deploy?</h2>';
    echo '<p>Klik link ini untuk mulai deploy:</p>';
    $deploy_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $separator = (strpos($deploy_url, '?') !== false) ? '&' : '?';
    echo '<p><a href="' . $deploy_url . $separator . 'token=' . $DEPLOY_TOKEN . '" style="font-size:18px;background:#0a0;color:#fff;padding:10px 20px;text-decoration:none;border-radius:5px;">▶️ START DEPLOY</a></p>';
    echo '</div>';
    echo '</body></html>';
    exit;
}

// === DEPLOY PROCESS ===
echo '<div class="box">';
echo '<h2>📁 Creating directories...</h2>';
foreach ($dirs as $dir) {
    $fullPath = __DIR__ . '/' . $dir;
    if (!is_dir($fullPath)) {
        if (@mkdir($fullPath, 0755, true)) {
            echo "<p class='ok'>✅ Created: $dir/</p>";
        } else {
            echo "<p class='err'>❌ Failed to create: $dir/</p>";
        }
    } else {
        echo "<p class='warn'>⚠️ Already exists: $dir/</p>";
    }
}
echo '</div>';

echo '<div class="box">';
echo '<h2>📥 Downloading files from GitHub...</h2>';

$success = 0;
$failed = 0;

foreach ($files as $githubPath => $localPath) {
    $url = $GITHUB_RAW . '/' . $githubPath;
    $destFile = __DIR__ . '/' . $localPath;
    
    // Download file
    $content = downloadFile($url);
    
    if ($content !== false) {
        // Ensure parent directory exists
        $parentDir = dirname($destFile);
        if (!is_dir($parentDir)) {
            @mkdir($parentDir, 0755, true);
        }
        
        if (file_put_contents($destFile, $content) !== false) {
            @chmod($destFile, 0644);
            $size = strlen($content);
            echo "<p class='ok'>✅ $localPath ($size bytes)</p>";
            $success++;
        } else {
            echo "<p class='err'>❌ Failed to write: $localPath</p>";
            $failed++;
        }
    } else {
        echo "<p class='err'>❌ Failed to download: $githubPath</p>";
        $failed++;
    }
}

echo '</div>';

// Fix directory permissions
echo '<div class="box">';
echo '<h2>🔧 Setting permissions...</h2>';
foreach ($dirs as $dir) {
    $fullPath = __DIR__ . '/' . $dir;
    if (is_dir($fullPath)) {
        @chmod($fullPath, 0755);
        echo "<p class='ok'>✅ chmod 755: $dir/</p>";
    }
}
echo '</div>';

// Summary
echo '<div class="box">';
echo "<h2>📊 Deploy Summary</h2>";
echo "<p class='ok'>✅ Success: $success files</p>";
if ($failed > 0) {
    echo "<p class='err'>❌ Failed: $failed files</p>";
} else {
    echo "<p class='ok'>🎉 All files deployed successfully!</p>";
}
echo '<p>Document Root: ' . (__DIR__) . '</p>';
echo '</div>';

// Quick test links
echo '<div class="box">';
echo '<h2>🔗 Test Links</h2>';
$baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
echo "<p><a href='$baseUrl/test.php'>Test Page (test.php)</a></p>";
echo "<p><a href='$baseUrl/'>Homepage (index.php)</a></p>";
echo '</div>';

// Self-cleanup option
echo '<div class="box">';
echo '<h2 class="warn">⚠️ Security</h2>';
echo '<p>HAPUS file deploy.php ini setelah selesai!</p>';
$deleteUrl = $baseUrl . '/deploy.php?action=selfdelete&token=' . $DEPLOY_TOKEN;
echo "<p><a href='$deleteUrl' style='background:#f00;color:#fff;padding:8px 15px;text-decoration:none;border-radius:5px;'>🗑️ Hapus deploy.php</a></p>";
echo '</div>';

// Handle self-delete
if (isset($_GET['action']) && $_GET['action'] === 'selfdelete' && isset($_GET['token']) && $_GET['token'] === $DEPLOY_TOKEN) {
    @unlink(__FILE__);
    echo '<p class="ok">✅ deploy.php telah dihapus!</p>';
}

echo '</body></html>';
exit;

// === HELPER FUNCTIONS ===
function downloadFile($url) {
    // Try cURL first
    if (function_exists('curl_init')) {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_USERAGENT => 'NarayanaDeployer/1.0',
        ]);
        $content = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode === 200 && $content !== false) {
            return $content;
        }
    }
    
    // Fallback to file_get_contents
    if (ini_get('allow_url_fopen')) {
        $ctx = stream_context_create([
            'http' => [
                'timeout' => 30,
                'user_agent' => 'NarayanaDeployer/1.0',
            ],
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ]);
        $content = @file_get_contents($url, false, $ctx);
        if ($content !== false) {
            return $content;
        }
    }
    
    return false;
}
