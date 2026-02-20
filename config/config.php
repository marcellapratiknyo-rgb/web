<?php
/**
 * NARAYANA KARIMUNJAWA - Configuration
 * Environment-aware config for local & production
 */

// Detect environment
$isLocal = (strpos($_SERVER['HTTP_HOST'] ?? 'localhost', 'localhost') !== false || 
            strpos($_SERVER['HTTP_HOST'] ?? '127.0.0.1', '127.0.0.1') !== false);

// Database Configuration
if ($isLocal) {
    // LOCAL DEVELOPMENT (XAMPP)
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'adf_web_narayana');
    define('DB_PORT', 3306);
} else {
    // PRODUCTION (Niagahoster)
    define('DB_HOST', 'localhost'); // Usually localhost even on shared hosting
    define('DB_USER', 'adfb2574_narayana'); // Your cPanel DB user
    define('DB_PASS', 'your_password_here'); // Will be set during deployment
    define('DB_NAME', 'adfb2574_web_narayana');
    define('DB_PORT', 3306);
}

// Site Configuration
define('SITE_NAME', 'Narayana Karimunjawa Hotel');
define('SITE_TAGLINE', 'Premium Beach Resort');
define('SITE_DESCRIPTION', 'Narayana Hotel - Karimunjawa Islands Most Beautiful Accommodation');

// URLs
$script_name = $_SERVER['SCRIPT_NAME'] ?? '/narayanakarimunjawa/public/index.php';
$base_path = dirname($script_name);
if ($base_path === '/') {
    $base_path = '';
}
define('SITE_URL', 'http' . ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 's' : '') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost'));
define('BASE_URL', $base_path);

// Business Information
define('BUSINESS_EMAIL', 'narayanahotelkarimunjawa@gmail.com');
define('BUSINESS_PHONE', '+62-812-2222-8590');
define('BUSINESS_ADDRESS', 'Karimunjawa, Jepara, Central Java, Indonesia');

// Payment Gateway (will be configured later)
define('MIDTRANS_MERCHANT_ID', 'MERCHANT_ID');
define('MIDTRANS_CLIENT_KEY', 'CLIENT_KEY');
define('MIDTRANS_SERVER_KEY', 'SERVER_KEY');
define('MIDTRANS_IS_PRODUCTION', !$isLocal);

// Email Configuration
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'your-email@gmail.com');
define('SMTP_PASS', 'your-app-password');

// Debug Mode
define('DEBUG_MODE', $isLocal);

// Session Configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', !$isLocal);
session_start();

// Error Reporting
if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', __DIR__ . '/../logs/error.log');
}

// Create logs directory if not exists
if (!is_dir(__DIR__ . '/../logs')) {
    mkdir(__DIR__ . '/../logs', 0755, true);
}

// PDO Database Connection
try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    if (DEBUG_MODE) {
        die('Database Connection Error: ' . $e->getMessage());
    } else {
        die('Database connection failed. Please contact support.');
    }
}

// Helper Functions
function dbQuery($sql, $params = []) {
    global $pdo;
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}

function dbFetch($sql, $params = []) {
    return dbQuery($sql, $params)->fetch();
}

function dbFetchAll($sql, $params = []) {
    return dbQuery($sql, $params)->fetchAll();
}

function dbInsert($table, $data) {
    global $pdo;
    $cols = implode(', ', array_keys($data));
    $vals = implode(', ', array_fill(0, count($data), '?'));
    $sql = "INSERT INTO $table ($cols) VALUES ($vals)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array_values($data));
    return $pdo->lastInsertId();
}

function dbUpdate($table, $data, $where, $whereParams = []) {
    global $pdo;
    $set = implode(', ', array_map(fn($k) => "$k = ?", array_keys($data)));
    $sql = "UPDATE $table SET $set WHERE $where";
    $params = array_merge(array_values($data), $whereParams);
    $stmt = $pdo->prepare($sql);
    return $stmt->execute($params);
}

function formatCurrency($amount) {
    return 'Rp ' . number_format($amount, 0, ',', '.');
}

function formatDate($date) {
    return date('d M Y', strtotime($date));
}

function redirect($url) {
    header('Location: ' . $url);
    exit;
}

function json_response($success, $data = null, $error = null) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'data' => $data,
        'error' => $error
    ]);
    exit;
}
