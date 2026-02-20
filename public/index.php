<?php
/**
 * NARAYANA KARIMUNJAWA — Homepage
 * Marriott-Inspired Clean Luxury Design
 */
// Flexible path: works on hosting (config inside webroot) and local dev (config outside public/)
$_cfg = __DIR__ . '/config/config.php';
if (!file_exists($_cfg)) $_cfg = dirname(__DIR__) . '/config/config.php';
require_once $_cfg;

$currentPage = 'home';

// Room types with availability
$roomTypes = dbFetchAll("
    SELECT rt.*, 
           COUNT(r.id) as total_rooms,
           SUM(CASE WHEN r.status = 'available' THEN 1 ELSE 0 END) as available_rooms
    FROM room_types rt
    LEFT JOIN rooms r ON rt.id = r.room_type_id
    GROUP BY rt.id
    ORDER BY rt.base_price DESC
");

// Today's stats
$today = date('Y-m-d');
$tomorrow = date('Y-m-d', strtotime('+1 day'));
$totalRooms = dbFetch("SELECT COUNT(*) as c FROM rooms")['c'] ?? 0;
$availableNow = dbFetch("
    SELECT COUNT(*) as c FROM rooms 
    WHERE status IN ('available','cleaning')
    AND id NOT IN (
        SELECT room_id FROM bookings 
        WHERE status IN ('pending','confirmed','checked_in')
        AND check_in_date <= ? AND check_out_date > ?
    )
", [$today, $today])['c'] ?? 0;

$roomIcons = ['King' => '👑', 'Queen' => '🌙', 'Twin' => '🛏️'];

include __DIR__ . '/includes/header.php';
?>

<!-- Hero -->
<section class="hero">
    <div class="hero-bg"></div>
    <div class="container">
        <div class="hero-content">
            <div class="hero-eyebrow">Karimunjawa Islands · Indonesia</div>
            <h1>Where the Ocean<br>Meets <em>Tranquility</em></h1>
            <p class="hero-text">Escape to our island resort surrounded by crystal-clear waters, pristine beaches, and the serenity of an untouched tropical paradise.</p>
            <div class="btn-group">
                <a href="<?= BASE_URL ?>/booking.php" class="btn btn-white btn-lg">Check Availability</a>
                <a href="<?= BASE_URL ?>/rooms.php" class="btn btn-outline-white btn-lg">View Rooms</a>
            </div>
        </div>
    </div>
</section>

<!-- Booking Bar -->
<div class="booking-bar">
    <div class="container">
        <div class="booking-bar-inner">
            <form class="booking-bar-form" action="<?= BASE_URL ?>/booking.php" method="GET">
                <div class="form-group">
                    <label>Check-in</label>
                    <input type="date" name="check_in" min="<?= $today ?>" value="<?= $today ?>" required>
                </div>
                <div class="form-group">
                    <label>Check-out</label>
                    <input type="date" name="check_out" min="<?= $tomorrow ?>" value="<?= $tomorrow ?>" required>
                </div>
                <div class="form-group">
                    <label>Guests</label>
                    <select name="guests">
                        <option value="1">1 Guest</option>
                        <option value="2" selected>2 Guests</option>
                        <option value="3">3 Guests</option>
                        <option value="4">4 Guests</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Find Rooms</button>
            </form>
        </div>
    </div>
</div>

<!-- Rooms -->
<section class="section">
    <div class="container">
        <div class="section-header text-center fade-in">
            <div class="section-eyebrow">Accommodations</div>
            <h2 class="section-title">Our Rooms</h2>
            <div class="divider center"></div>
            <p class="section-desc center">Every room is designed for comfort and relaxation with modern amenities and island charm.</p>
        </div>

        <div class="rooms-grid">
            <?php foreach ($roomTypes as $room):
                $amenities = $room['amenities'] ? explode(',', $room['amenities']) : [];
                $icon = $roomIcons[$room['type_name']] ?? '🏨';
                $avail = (int)$room['available_rooms'];
                $total = (int)$room['total_rooms'];
                
                if ($avail >= 3) { $ac = 'available'; $at = $avail . ' Available'; }
                elseif ($avail > 0) { $ac = 'limited'; $at = 'Only ' . $avail . ' Left'; }
                else { $ac = 'full'; $at = 'Fully Booked'; }
            ?>
            <div class="room-card fade-in">
                <div class="room-card-image">
                    <span class="room-type-badge"><?= htmlspecialchars($room['type_name']) ?></span>
                    <div class="room-visual"><?= $icon ?></div>
                </div>
                <div class="room-card-body">
                    <h3><?= htmlspecialchars($room['type_name']) ?> Room</h3>
                    <div class="room-meta">
                        <span class="room-meta-item"><i class="fas fa-user"></i> Up to <?= $room['max_occupancy'] ?> guests</span>
                        <span class="room-meta-item"><i class="fas fa-door-open"></i> <?= $total ?> rooms</span>
                    </div>
                    <div class="room-amenities">
                        <?php foreach (array_slice($amenities, 0, 4) as $a): ?>
                            <span><?= htmlspecialchars(trim($a)) ?></span>
                        <?php endforeach; ?>
                    </div>
                    <div class="room-card-footer">
                        <div class="room-price"><?= formatCurrency($room['base_price']) ?><small>/night</small></div>
                        <span class="avail-badge <?= $ac ?>"><span class="avail-dot"></span><?= $at ?></span>
                    </div>
                    <div style="margin-top:16px;">
                        <a href="<?= BASE_URL ?>/booking.php?room_type=<?= $room['id'] ?>" class="btn btn-primary btn-block">Book This Room</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Features -->
<section class="section section-alt">
    <div class="container">
        <div class="section-header text-center fade-in">
            <div class="section-eyebrow">Experience</div>
            <h2 class="section-title">Why Narayana</h2>
            <div class="divider center"></div>
        </div>

        <div class="features-grid">
            <div class="feature-card fade-in">
                <div class="feature-icon"><i class="fas fa-water"></i></div>
                <h4>Beachfront Location</h4>
                <p>Step directly onto pristine white sandy shores with crystal-clear turquoise waters.</p>
            </div>
            <div class="feature-card fade-in">
                <div class="feature-icon"><i class="fas fa-concierge-bell"></i></div>
                <h4>Personalised Service</h4>
                <p>Dedicated staff ensuring every aspect of your stay is tailored to your preferences.</p>
            </div>
            <div class="feature-card fade-in">
                <div class="feature-icon"><i class="fas fa-anchor"></i></div>
                <h4>Island Activities</h4>
                <p>Snorkelling, diving, island hopping, and sunset cruises arranged for our guests.</p>
            </div>
            <div class="feature-card fade-in">
                <div class="feature-icon"><i class="fas fa-utensils"></i></div>
                <h4>Fresh Cuisine</h4>
                <p>Freshly caught seafood and authentic local dishes prepared by skilled chefs.</p>
            </div>
        </div>
    </div>
</section>

<!-- Stats -->
<section class="section-dark">
    <div class="container">
        <div class="stats-bar">
            <div class="stat-item fade-in">
                <div class="stat-value"><?= $totalRooms ?></div>
                <div class="stat-label">Total Rooms</div>
            </div>
            <div class="stat-item fade-in">
                <div class="stat-value"><?= $availableNow ?></div>
                <div class="stat-label">Available Tonight</div>
            </div>
            <div class="stat-item fade-in">
                <div class="stat-value"><?= count($roomTypes) ?></div>
                <div class="stat-label">Room Categories</div>
            </div>
            <div class="stat-item fade-in">
                <div class="stat-value">4.8</div>
                <div class="stat-label">Guest Rating</div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="section">
    <div class="container">
        <div class="section-header text-center fade-in">
            <div class="section-eyebrow">Reviews</div>
            <h2 class="section-title">What Our Guests Say</h2>
            <div class="divider center"></div>
        </div>

        <div class="testimonials-grid">
            <div class="testimonial-card fade-in">
                <div class="testimonial-stars">★★★★★</div>
                <blockquote>"The most beautiful place I've ever stayed. Waking up to the sound of waves and stepping onto that white sand — simply magical."</blockquote>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">SC</div>
                    <div>
                        <div class="testimonial-name">Sarah Chen</div>
                        <div class="testimonial-origin">Singapore</div>
                    </div>
                </div>
            </div>
            <div class="testimonial-card fade-in">
                <div class="testimonial-stars">★★★★★</div>
                <blockquote>"Incredible service and attention to detail. The staff arranged a private island tour that was the highlight of our trip."</blockquote>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">MR</div>
                    <div>
                        <div class="testimonial-name">Marco Rossi</div>
                        <div class="testimonial-origin">Italy</div>
                    </div>
                </div>
            </div>
            <div class="testimonial-card fade-in">
                <div class="testimonial-stars">★★★★★</div>
                <blockquote>"A hidden paradise. Karimunjawa is still so unspoiled and Narayana made everything easy. Will definitely return."</blockquote>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">AW</div>
                    <div>
                        <div class="testimonial-name">Andi Wijaya</div>
                        <div class="testimonial-origin">Jakarta, Indonesia</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section">
    <div class="container">
        <div class="section-eyebrow" style="color:var(--gold-light);">Ready to escape?</div>
        <h2>Begin Your Island Journey</h2>
        <p>Book your stay and experience the magic of Karimunjawa.</p>
        <div class="btn-group" style="justify-content:center;">
            <a href="<?= BASE_URL ?>/booking.php" class="btn btn-gold btn-lg">Reserve Your Room</a>
            <a href="https://wa.me/<?= BUSINESS_WHATSAPP ?>?text=Hi%20Narayana%2C%20I%27d%20like%20to%20inquire%20about%20a%20reservation" target="_blank" class="btn btn-outline-white btn-lg">
                <i class="fab fa-whatsapp"></i> WhatsApp
            </a>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
