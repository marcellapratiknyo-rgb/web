<?php
/**
 * NARAYANA KARIMUNJAWA — Rooms
 * Marriott-style room showcase with real-time availability
 */
require_once dirname(__DIR__) . '/config/config.php';

$currentPage = 'rooms';
$pageTitle = 'Rooms';

$roomTypes = dbFetchAll("
    SELECT rt.*,
           COUNT(r.id) as total_rooms,
           SUM(CASE WHEN r.status = 'available' THEN 1 ELSE 0 END) as available_rooms
    FROM room_types rt
    LEFT JOIN rooms r ON rt.id = r.room_type_id
    GROUP BY rt.id
    ORDER BY rt.base_price DESC
");

$rooms = dbFetchAll("
    SELECT r.*, rt.type_name, rt.base_price
    FROM rooms r
    JOIN room_types rt ON r.room_type_id = rt.id
    ORDER BY r.room_number ASC
");

$roomIcons = ['King' => '👑', 'Queen' => '🌙', 'Twin' => '🛏️'];

$roomDescriptions = [
    'King' => 'Our finest accommodation featuring a luxurious king-size bed, elegant furnishings, and panoramic views. Perfect for couples seeking the ultimate island retreat.',
    'Queen' => 'A beautifully appointed room with a comfortable queen-size bed and modern amenities. Enjoy serene island views and the gentle sound of ocean waves.',
    'Twin' => 'Ideal for friends or family, our twin rooms feature two comfortable single beds in a spacious setting with all the comforts for a relaxing stay.',
];

include __DIR__ . '/includes/header.php';
?>

<!-- Page Hero -->
<section class="page-hero">
    <div class="container">
        <div class="section-eyebrow" style="color:var(--gold-light);">Accommodations</div>
        <h1>Our Rooms</h1>
        <p>Thoughtfully designed spaces where island comfort meets refined elegance.</p>
    </div>
</section>

<!-- Room Types -->
<section class="section">
    <div class="container">
        <?php foreach ($roomTypes as $i => $room):
            $amenities = $room['amenities'] ? array_map('trim', explode(',', $room['amenities'])) : [];
            $icon = $roomIcons[$room['type_name']] ?? '🏨';
            $desc = $roomDescriptions[$room['type_name']] ?? 'A comfortable room designed for your perfect island stay.';
            $avail = (int)$room['available_rooms'];
            $reverse = $i % 2 !== 0;
            
            if ($avail >= 3) { $ac = 'available'; $at = $avail . ' rooms available'; }
            elseif ($avail > 0) { $ac = 'limited'; $at = 'Only ' . $avail . ' remaining'; }
            else { $ac = 'full'; $at = 'Fully booked today'; }

            $typeRooms = array_filter($rooms, fn($r) => $r['room_type_id'] == $room['id']);
        ?>
        <div class="room-detail <?= $reverse ? 'reverse' : '' ?> fade-in">
            <!-- Image -->
            <div class="room-detail-image">
                <span class="room-emoji"><?= $icon ?></span>
                <div style="position:absolute; bottom:16px; left:16px;">
                    <span class="avail-badge <?= $ac ?>"><span class="avail-dot"></span><?= $at ?></span>
                </div>
            </div>

            <!-- Info -->
            <div class="room-detail-info">
                <div class="section-eyebrow"><?= htmlspecialchars($room['type_name']) ?> Room</div>
                <h2><?= htmlspecialchars($room['type_name']) ?></h2>
                <div class="divider"></div>
                <p style="color:var(--warm-gray); line-height:1.8; margin-bottom:20px;"><?= $desc ?></p>

                <div class="room-specs">
                    <div class="room-spec"><i class="fas fa-user"></i> Up to <?= $room['max_occupancy'] ?> Guests</div>
                    <div class="room-spec"><i class="fas fa-door-open"></i> <?= (int)$room['total_rooms'] ?> Rooms</div>
                    <div class="room-spec"><i class="fas fa-clock"></i> Check-in <?= BUSINESS_CHECKIN_TIME ?></div>
                    <div class="room-spec"><i class="fas fa-wifi"></i> Free WiFi</div>
                </div>

                <!-- Amenities -->
                <div class="room-amenities">
                    <?php foreach ($amenities as $a): ?>
                        <span><?= htmlspecialchars($a) ?></span>
                    <?php endforeach; ?>
                </div>

                <!-- Room Status -->
                <div style="margin-top:8px;">
                    <div style="font-size:11px; font-weight:600; letter-spacing:1px; text-transform:uppercase; color:var(--mid-gray); margin-bottom:8px;">Room Status</div>
                    <div class="room-status-grid">
                        <?php foreach ($typeRooms as $tr):
                            $sc = match($tr['status']) {
                                'available' => 'available',
                                'occupied' => 'occupied',
                                'cleaning' => 'cleaning',
                                default => 'maintenance'
                            };
                        ?>
                        <div class="room-status-box <?= $sc ?>" title="Room <?= $tr['room_number'] ?> — <?= ucfirst($tr['status']) ?>">
                            <?= $tr['room_number'] ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="status-legend">
                        <span class="legend-available">Available</span>
                        <span class="legend-occupied">Occupied</span>
                        <span class="legend-cleaning">Cleaning</span>
                    </div>
                </div>

                <!-- Price & CTA -->
                <div class="room-detail-footer">
                    <div class="room-price"><?= formatCurrency($room['base_price']) ?><small> /night</small></div>
                    <a href="<?= BASE_URL ?>/booking.php?room_type=<?= $room['id'] ?>" class="btn btn-primary">Book Now</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Amenities Overview -->
<section class="section section-alt">
    <div class="container">
        <div class="section-header text-center fade-in">
            <div class="section-eyebrow">Amenities</div>
            <h2 class="section-title">Every Room Includes</h2>
            <div class="divider center"></div>
        </div>
        <div class="features-grid">
            <?php
            $amenList = [
                ['icon' => 'fa-snowflake', 'name' => 'Air Conditioning', 'desc' => 'Climate-controlled comfort'],
                ['icon' => 'fa-wifi', 'name' => 'High-Speed WiFi', 'desc' => 'Stay connected always'],
                ['icon' => 'fa-tv', 'name' => 'Flat Screen TV', 'desc' => 'In-room entertainment'],
                ['icon' => 'fa-shower', 'name' => 'Hot Water', 'desc' => 'Rain shower experience'],
                ['icon' => 'fa-bed', 'name' => 'Premium Bedding', 'desc' => 'Luxury linens & pillows'],
                ['icon' => 'fa-broom', 'name' => 'Daily Housekeeping', 'desc' => 'Pristine & refreshed daily'],
            ];
            foreach ($amenList as $am):
            ?>
            <div class="feature-card fade-in" style="text-align:center;">
                <div class="feature-icon" style="margin:0 auto 16px;"><i class="fas <?= $am['icon'] ?>"></i></div>
                <h4><?= $am['name'] ?></h4>
                <p><?= $am['desc'] ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section">
    <div class="container">
        <h2>Find Your Perfect Room</h2>
        <p>Check availability for your desired dates and reserve your island escape.</p>
        <a href="<?= BASE_URL ?>/booking.php" class="btn btn-gold btn-lg">Check Availability</a>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
