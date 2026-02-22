<?php
/**
 * HERO BACKGROUND SYNC — One-time upload tool
 * Upload this to narayanakarimunjawa.com root via cPanel File Manager
 * Open: https://narayanakarimunjawa.com/hero-sync.php
 * HAPUS setelah selesai!
 */
header('Content-Type: text/html; charset=utf-8');

// DB Config
$dbHost = 'localhost';
$dbUser = 'adfb2574_adfsystem';
$dbPass = '@Nnoc2025';
$dbName = 'adfb2574_narayana_hotel';

echo '<!DOCTYPE html><html><head><title>Hero Sync</title>';
echo '<style>body{font-family:sans-serif;max-width:600px;margin:40px auto;padding:20px;background:#f5f5f5}';
echo '.card{background:#fff;padding:30px;border-radius:12px;box-shadow:0 2px 10px rgba(0,0,0,.1)}';
echo '.ok{color:green;font-weight:bold}.err{color:red;font-weight:bold}';
echo 'input[type=file]{margin:10px 0}button{background:#0c2340;color:#fff;border:none;padding:12px 24px;border-radius:8px;cursor:pointer;font-size:16px}';
echo 'button:hover{background:#1a3a5c}img{max-width:100%;border-radius:8px;margin:10px 0}</style></head><body>';
echo '<div class="card">';
echo '<h2>🖼️ Hero Background Sync</h2>';

try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    
    // Show current hero bg
    $stmt = $pdo->prepare("SELECT setting_value FROM settings WHERE setting_key = 'web_hero_background'");
    $stmt->execute();
    $current = $stmt->fetchColumn();
    
    if ($current) {
        echo '<p>Current hero: <strong>' . htmlspecialchars($current) . '</strong></p>';
        if (file_exists(__DIR__ . '/' . $current)) {
            echo '<p class="ok">✅ File exists on server</p>';
            echo '<img src="/' . htmlspecialchars($current) . '" alt="Current Hero">';
        } else {
            echo '<p class="err">❌ File NOT found on server!</p>';
        }
    } else {
        echo '<p class="err">No hero background set in database</p>';
    }
    
    // Handle upload
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['hero_img'])) {
        if ($_FILES['hero_img']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['hero_img']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                $uploadDir = __DIR__ . '/uploads/hero/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                
                $newName = 'hero-bg-' . time() . '.' . $ext;
                $destPath = $uploadDir . $newName;
                
                if (move_uploaded_file($_FILES['hero_img']['tmp_name'], $destPath)) {
                    $relativePath = 'uploads/hero/' . $newName;
                    
                    // Update database
                    $stmt = $pdo->prepare("INSERT INTO settings (setting_key, setting_value, setting_type, description) 
                                VALUES ('web_hero_background', ?, 'text', 'Website Hero Background Image') 
                                ON DUPLICATE KEY UPDATE setting_value = ?");
                    $stmt->execute([$relativePath, $relativePath]);
                    
                    // Delete old file
                    if ($current && file_exists(__DIR__ . '/' . $current)) {
                        @unlink(__DIR__ . '/' . $current);
                    }
                    
                    echo '<p class="ok">✅ Hero background updated! → ' . htmlspecialchars($relativePath) . '</p>';
                    echo '<img src="/' . htmlspecialchars($relativePath) . '" alt="New Hero">';
                    echo '<p><a href="https://narayanakarimunjawa.com">← View Website</a></p>';
                    echo '</div></body></html>';
                    exit;
                } else {
                    echo '<p class="err">Upload failed!</p>';
                }
            } else {
                echo '<p class="err">Format not allowed. Use: jpg, png, webp</p>';
            }
        } else {
            echo '<p class="err">Upload error: ' . $_FILES['hero_img']['error'] . '</p>';
        }
    }
    
    echo '<hr>';
    echo '<h3>Upload New Hero Background</h3>';
    echo '<form method="POST" enctype="multipart/form-data">';
    echo '<input type="file" name="hero_img" accept="image/jpeg,image/png,image/webp" required><br>';
    echo '<button type="submit">Upload & Set Hero Background</button>';
    echo '</form>';
    
} catch (Exception $e) {
    echo '<p class="err">DB Error: ' . $e->getMessage() . '</p>';
}

echo '</div></body></html>';
