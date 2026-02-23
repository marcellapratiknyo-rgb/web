<?php
/**
 * PATCH SCRIPT — Downloads missing files and updates deploy.php
 * Upload this to hosting root via cPanel File Manager
 * Run: https://narayanakarimunjawa.com/patch.php
 * Auto-deletes after running.
 */
header('Content-Type: text/html; charset=utf-8');
echo '<h2>Patching Narayana...</h2>';

$GITHUB_RAW = 'https://raw.githubusercontent.com/marcellapratiknyo-rgb/web/main';

// Files to patch
$patchFiles = [
    'public/assets/css/style.css' => 'assets/css/style.css',
];

// Create missing directories
$dirs = ['uploads/destinations', 'uploads/logo', 'uploads/favicon'];
foreach ($dirs as $d) {
    $p = __DIR__ . '/' . $d;
    if (!is_dir($p)) { @mkdir($p, 0755, true); echo "<p>✅ Created: $d/</p>"; }
}

// Download and deploy files
foreach ($patchFiles as $src => $dest) {
    $url = $GITHUB_RAW . '/' . $src;
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_USERAGENT => 'NarayanaPatch/1.0',
    ]);
    $content = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($code === 200 && $content) {
        $destPath = __DIR__ . '/' . $dest;
        $dir = dirname($destPath);
        if (!is_dir($dir)) @mkdir($dir, 0755, true);
        file_put_contents($destPath, $content);
        $size = strlen($content);
        echo "<p style='color:green'>✅ $dest ($size bytes)</p>";
    } else {
        echo "<p style='color:red'>❌ Failed: $dest (HTTP $code)</p>";
    }
}

echo '<h3>✅ Patch complete!</h3>';
echo '<p><a href="/">← Go to Homepage</a> | <a href="/destinations.php">Destinations Page</a></p>';

// Self-delete
@unlink(__FILE__);
echo '<p style="color:gray;font-size:12px">patch.php auto-deleted.</p>';
