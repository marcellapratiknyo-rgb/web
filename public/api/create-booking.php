<?php
/**
 * API: Create Booking
 * Creates a new booking in the hotel system from the public website
 */
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

// Flexible path: works on hosting (config inside webroot) and local dev (config outside public/)
$_cfg = dirname(__DIR__) . '/config/config.php';
if (!file_exists($_cfg)) $_cfg = dirname(__DIR__, 2) . '/config/config.php';
require_once $_cfg;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

try {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        throw new Exception('Invalid request data');
    }
    
    // Validate required fields
    $required = ['room_id', 'check_in', 'check_out', 'guest_name', 'guest_email', 'guest_phone'];
    foreach ($required as $field) {
        if (empty($input[$field])) {
            throw new Exception("Missing required field: $field");
        }
    }
    
    $roomId = (int)$input['room_id'];
    $checkIn = $input['check_in'];
    $checkOut = $input['check_out'];
    $guestName = trim($input['guest_name']);
    $guestEmail = trim($input['guest_email']);
    $guestPhone = trim($input['guest_phone']);
    $guests = (int)($input['guests'] ?? 2);
    $idCardType = $input['id_card_type'] ?? 'ktp';
    $idCardNumber = trim($input['id_card_number'] ?? '');
    $nationality = trim($input['nationality'] ?? 'Indonesia');
    $specialRequest = trim($input['special_request'] ?? '');
    
    // Validate dates
    if (strtotime($checkIn) >= strtotime($checkOut)) {
        throw new Exception('Check-out must be after check-in');
    }
    if (strtotime($checkIn) < strtotime(date('Y-m-d'))) {
        throw new Exception('Check-in cannot be in the past');
    }
    
    // Validate email
    if (!filter_var($guestEmail, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email address');
    }
    
    // Check room exists and get price
    $room = dbFetch("
        SELECT r.*, rt.type_name, rt.base_price 
        FROM rooms r 
        JOIN room_types rt ON r.room_type_id = rt.id 
        WHERE r.id = ?
    ", [$roomId]);
    
    if (!$room) {
        throw new Exception('Room not found');
    }
    
    // Check room is still available for these dates
    $conflict = dbFetch("
        SELECT COUNT(*) as count FROM bookings 
        WHERE room_id = ? 
        AND status IN ('pending', 'confirmed', 'checked_in')
        AND check_in_date < ? AND check_out_date > ?
    ", [$roomId, $checkOut, $checkIn]);
    
    if ($conflict['count'] > 0) {
        throw new Exception('This room is no longer available for your selected dates. Please choose another room.');
    }
    
    // Calculate pricing
    $nights = (new DateTime($checkIn))->diff(new DateTime($checkOut))->days;
    $roomPrice = $room['base_price'];
    $totalPrice = $roomPrice * $nights;
    
    // Generate booking code
    $bookingCode = 'BK-' . date('Ymd') . '-' . str_pad(random_int(1000, 9999), 4, '0', STR_PAD_LEFT);
    
    $pdo->beginTransaction();
    
    try {
        // Create or find guest
        $existingGuest = dbFetch("SELECT id FROM guests WHERE email = ? OR phone = ?", [$guestEmail, $guestPhone]);
        
        if ($existingGuest) {
            $guestId = $existingGuest['id'];
            // Update guest info
            dbUpdate('guests', [
                'guest_name' => $guestName,
                'phone' => $guestPhone,
                'email' => $guestEmail,
                'id_card_type' => $idCardType,
                'id_card_number' => $idCardNumber,
                'nationality' => $nationality,
            ], 'id = ?', [$guestId]);
        } else {
            $guestId = dbInsert('guests', [
                'guest_name' => $guestName,
                'phone' => $guestPhone,
                'email' => $guestEmail,
                'id_card_type' => $idCardType,
                'id_card_number' => $idCardNumber,
                'nationality' => $nationality,
            ]);
        }
        
        // Create booking
        $bookingId = dbInsert('bookings', [
            'booking_code' => $bookingCode,
            'guest_id' => $guestId,
            'room_id' => $roomId,
            'check_in_date' => $checkIn,
            'check_out_date' => $checkOut,
            'adults' => $guests,
            'children' => 0,
            'room_price' => $roomPrice,
            'total_nights' => $nights,
            'total_price' => $totalPrice,
            'discount' => 0,
            'final_price' => $totalPrice,
            'status' => 'confirmed',
            'payment_status' => 'unpaid',
            'paid_amount' => 0,
            'booking_source' => 'online',
            'special_request' => $specialRequest,
            'notes' => 'Booked via website',
        ]);
        
        $pdo->commit();
        
        echo json_encode([
            'success' => true,
            'data' => [
                'booking_id' => $bookingId,
                'booking_code' => $bookingCode,
                'room_type' => $room['type_name'],
                'room_number' => $room['room_number'],
                'check_in' => $checkIn,
                'check_out' => $checkOut,
                'nights' => $nights,
                'total_price' => $totalPrice,
                'guest_name' => $guestName,
            ],
            'message' => 'Reservation confirmed successfully!'
        ]);
        
    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
