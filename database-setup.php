<?php
/**
 * Database Setup Script
 * Creates the adf_web_narayana database and imports schema
 */

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'adf_web_narayana';

// Create connection without database selection
$conn = new mysqli($host, $user, $pass);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
echo "Creating database: $dbname\n";
$sql = "CREATE DATABASE IF NOT EXISTS $dbname CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";

if ($conn->query($sql) === TRUE) {
    echo "✓ Database created successfully\n";
} else {
    echo "✗ Error creating database: " . $conn->error . "\n";
    exit(1);
}

// Select database
$conn->select_db($dbname);
$conn->set_charset("utf8mb4");

echo "\nCreating tables...\n";

// Create tables
$tables = [
    // Room Types
    "CREATE TABLE IF NOT EXISTS room_types (
        id INT PRIMARY KEY AUTO_INCREMENT,
        type_name VARCHAR(100) NOT NULL UNIQUE,
        description TEXT,
        base_price INT NOT NULL,
        max_guests INT DEFAULT 2,
        amenities JSON,
        image_url VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
    
    // Rooms
    "CREATE TABLE IF NOT EXISTS rooms (
        id INT PRIMARY KEY AUTO_INCREMENT,
        room_type_id INT NOT NULL,
        room_number VARCHAR(20) NOT NULL UNIQUE,
        floor INT,
        status ENUM('available', 'maintenance', 'occupied') DEFAULT 'available',
        notes TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (room_type_id) REFERENCES room_types(id) ON DELETE CASCADE,
        INDEX idx_room_type (room_type_id),
        INDEX idx_status (status)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
    
    // Room Availability
    "CREATE TABLE IF NOT EXISTS room_availability (
        id INT PRIMARY KEY AUTO_INCREMENT,
        room_id INT NOT NULL,
        date DATE NOT NULL,
        is_available BOOLEAN DEFAULT TRUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE,
        UNIQUE KEY unique_room_date (room_id, date),
        INDEX idx_date (date)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
    
    // Guests
    "CREATE TABLE IF NOT EXISTS guests (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(150) NOT NULL,
        email VARCHAR(100),
        phone VARCHAR(20),
        nationality VARCHAR(100),
        id_card_number VARCHAR(50),
        address TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_email (email),
        INDEX idx_phone (phone)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
    
    // Bookings
    "CREATE TABLE IF NOT EXISTS bookings (
        id INT PRIMARY KEY AUTO_INCREMENT,
        booking_code VARCHAR(20) NOT NULL UNIQUE,
        guest_id INT NOT NULL,
        room_id INT NOT NULL,
        check_in DATE NOT NULL,
        check_out DATE NOT NULL,
        number_of_guests INT NOT NULL,
        number_of_nights INT NOT NULL,
        room_price_per_night INT NOT NULL,
        total_price INT NOT NULL,
        special_request TEXT,
        status ENUM('pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled') DEFAULT 'pending',
        cancellation_reason TEXT,
        cancelled_at TIMESTAMP NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (guest_id) REFERENCES guests(id) ON DELETE RESTRICT,
        FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE RESTRICT,
        INDEX idx_booking_code (booking_code),
        INDEX idx_guest (guest_id),
        INDEX idx_room (room_id),
        INDEX idx_status (status),
        INDEX idx_check_in (check_in),
        INDEX idx_check_out (check_out)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
    
    // Payments
    "CREATE TABLE IF NOT EXISTS payments (
        id INT PRIMARY KEY AUTO_INCREMENT,
        booking_id INT NOT NULL,
        amount INT NOT NULL,
        payment_method ENUM('bank_transfer', 'credit_card', 'midtrans', 'cash') DEFAULT 'midtrans',
        transaction_id VARCHAR(100),
        status ENUM('pending', 'processing', 'success', 'failed') DEFAULT 'pending',
        paid_at TIMESTAMP NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
        INDEX idx_booking (booking_id),
        INDEX idx_status (status),
        INDEX idx_transaction (transaction_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
    
    // Reviews
    "CREATE TABLE IF NOT EXISTS reviews (
        id INT PRIMARY KEY AUTO_INCREMENT,
        booking_id INT NOT NULL,
        guest_id INT NOT NULL,
        rating INT CHECK (rating >= 1 AND rating <= 5),
        title VARCHAR(200),
        review_text TEXT,
        cleanliness INT,
        comfort INT,
        service INT,
        value_for_money INT,
        is_published BOOLEAN DEFAULT FALSE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
        FOREIGN KEY (guest_id) REFERENCES guests(id) ON DELETE CASCADE,
        INDEX idx_booking (booking_id),
        INDEX idx_published (is_published)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
    
    // Settings
    "CREATE TABLE IF NOT EXISTS settings (
        id INT PRIMARY KEY AUTO_INCREMENT,
        setting_key VARCHAR(100) NOT NULL UNIQUE,
        setting_value LONGTEXT,
        description VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
    
    // Email Templates
    "CREATE TABLE IF NOT EXISTS email_templates (
        id INT PRIMARY KEY AUTO_INCREMENT,
        template_key VARCHAR(100) NOT NULL UNIQUE,
        subject VARCHAR(255),
        body LONGTEXT,
        is_active BOOLEAN DEFAULT TRUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
    
    // Promo Codes
    "CREATE TABLE IF NOT EXISTS promo_codes (
        id INT PRIMARY KEY AUTO_INCREMENT,
        code VARCHAR(50) NOT NULL UNIQUE,
        discount_type ENUM('percentage', 'fixed') DEFAULT 'percentage',
        discount_value INT NOT NULL,
        min_booking_amount INT,
        max_usage INT,
        usage_count INT DEFAULT 0,
        valid_from DATE,
        valid_until DATE,
        is_active BOOLEAN DEFAULT TRUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_code (code),
        INDEX idx_active (is_active)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
];

$count = 0;
foreach ($tables as $table_sql) {
    if ($conn->query($table_sql) === TRUE) {
        $count++;
    } else {
        echo "✗ Error: " . $conn->error . "\n";
    }
}

echo "✓ Created $count tables\n";

// Insert sample data
echo "\nInserting sample data...\n";

$inserts = [
    "INSERT IGNORE INTO room_types (id, type_name, description, base_price, max_guests, amenities) VALUES
    (1, 'Standard Room', 'Comfortable room with basic amenities', 450000, 2, '[\"WiFi\", \"AC\", \"Hot Water\", \"TV\"]'),
    (2, 'Deluxe Room', 'Spacious room with ocean view', 650000, 2, '[\"WiFi\", \"AC\", \"Hot Water\", \"TV\", \"Balcony\", \"Mini Bar\"]'),
    (3, 'Suite', 'Premium suite with living area', 950000, 4, '[\"WiFi\", \"AC\", \"Hot Water\", \"TV\", \"Balcony\", \"Mini Bar\", \"Bath Tub\", \"Work Desk\"]'),
    (4, 'Villa', 'Private villa with private pool', 1500000, 6, '[\"WiFi\", \"AC\", \"Hot Water\", \"TV\", \"Private Pool\", \"Kitchen\", \"Terrace\", \"Living Area\"]')",
    
    "INSERT IGNORE INTO settings (setting_key, setting_value, description) VALUES
    ('site_name', 'Narayana Karimunjawa Hotel', 'Website title'),
    ('site_description', 'Premium Beach Resort in Karimunjawa Islands', 'Website meta description'),
    ('company_phone', '+62-812-2222-8590', 'Company phone number'),
    ('company_email', 'narayanahotelkarimunjawa@gmail.com', 'Company email'),
    ('company_address', 'Karimunjawa, Jepara, Central Java, Indonesia', 'Company address'),
    ('currency', 'IDR', 'Currency used'),
    ('timezone', 'Asia/Jakarta', 'Timezone'),
    ('check_in_time', '14:00', 'Default check-in time'),
    ('check_out_time', '11:00', 'Default check-out time'),
    ('min_booking_days', '1', 'Minimum booking nights'),
    ('cancellation_policy', 'Free cancellation up to 7 days before check-in', 'Cancellation policy text')"
];

$insert_count = 0;
foreach ($inserts as $insert_sql) {
    if ($conn->query($insert_sql) === TRUE) {
        $insert_count++;
    } else {
        echo "✗ Error: " . $conn->error . "\n";
    }
}

echo "✓ Inserted data in $insert_count statement(s)\n";
echo "\n✓✓✓ Database setup completed successfully! ✓✓✓\n";
echo "Database: $dbname\n";
echo "Tables created: $count\n";

$conn->close();
?>
