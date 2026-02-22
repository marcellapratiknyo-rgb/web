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

// Load hero settings from database
$heroSettings = dbFetchAll("SELECT setting_key, setting_value FROM settings WHERE setting_key LIKE 'web_hero_%'");
$hero = [];
foreach ($heroSettings as $setting) {
    $hero[$setting['setting_key']] = $setting['setting_value'];
}

// Extract hero values with defaults
$heroBg = $hero['web_hero_background'] ?? '';
$heroAccent = $hero['web_hero_accent'] ?? 'Karimunjawa Islands · Indonesia';
$heroTitle = $hero['web_hero_title'] ?? 'Where the Ocean<br>Meets <em>Tranquility</em>';
$heroSubtitle = $hero['web_hero_subtitle'] ?? 'Escape to our island resort surrounded by crystal-clear waters, pristine beaches, and the serenity of an untouched tropical paradise.';

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

// Load room gallery images from settings
$gallerySettings = dbFetchAll("SELECT setting_key, setting_value FROM settings WHERE setting_key LIKE 'web_room_gallery_%' OR setting_key LIKE 'web_room_primary_%'");
$roomGalleries = [];
$roomPrimary = [];
foreach ($gallerySettings as $gs) {
    if (strpos($gs['setting_key'], 'web_room_gallery_') === 0) {
        $type = str_replace('web_room_gallery_', '', $gs['setting_key']);
        $roomGalleries[ucfirst($type)] = json_decode($gs['setting_value'], true) ?: [];
    }
    if (strpos($gs['setting_key'], 'web_room_primary_') === 0) {
        $type = str_replace('web_room_primary_', '', $gs['setting_key']);
        $roomPrimary[ucfirst($type)] = $gs['setting_value'];
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<!-- Hero -->
<section class="hero">
    <div class="hero-bg" <?php if (!empty($heroBg)): ?>style="background-image: url('<?= BASE_URL ?>/<?= htmlspecialchars($heroBg) ?>');"<?php endif; ?>></div>
    <div class="container">
        <div class="hero-content">
            <div class="hero-eyebrow"><?= htmlspecialchars($heroAccent) ?></div>
            <h1><?= $heroTitle ?></h1>
            <p class="hero-text"><?= htmlspecialchars($heroSubtitle) ?></p>
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
                    <label for="bb_checkin">Check-in</label>
                    <input type="date" id="bb_checkin" name="check_in" min="<?= $today ?>" value="<?= $today ?>" required>
                </div>
                <div class="form-group">
                    <label for="bb_checkout">Check-out</label>
                    <input type="date" id="bb_checkout" name="check_out" min="<?= $tomorrow ?>" value="<?= $tomorrow ?>" required>
                </div>
                <div class="form-group">
                    <label for="bb_guests">Guests</label>
                    <select id="bb_guests" name="guests">
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

<!-- About Narayana -->
<section class="section about-section">
    <div class="container">
        <div class="about-layout fade-in">
            <div class="about-content">
                <div class="section-eyebrow">Tentang Kami</div>
                <h2 class="section-title">Narayana Karimunjawa</h2>
                <div class="divider"></div>
                <p class="about-lead">Hotel baru di Karimunjawa dengan <strong>lokasi paling strategis</strong> — dekat kota, akses mudah, dan hanya beberapa langkah dari spot sunset terbaik di pulau.</p>
                <p class="about-text">Narayana hadir dengan konsep bangunan unik yang memadukan kenyamanan modern dan ketenangan alam. Nikmati pemandangan gunung yang menakjubkan langsung dari hotel, suasana yang tenang jauh dari keramaian, namun tetap dekat dengan segala fasilitas yang Anda butuhkan.</p>
                <div class="about-highlight">
                    <div class="about-highlight-icon"><i class="fas fa-gem"></i></div>
                    <div>
                        <strong>Semua Kebutuhan Liburan dalam Satu Tempat</strong>
                        <p>Kami menyediakan layanan lengkap — dari hotel dengan pemandangan spektakuler, restoran dengan hidangan khas laut segar, rental motor untuk eksplorasi pulau, hingga trip laut ke spot snorkeling dan pulau-pulau tersembunyi. Semua ada di satu tempat, tanpa repot.</p>
                    </div>
                </div>
            </div>
            <div class="about-features">
                <div class="about-feature-item fade-in">
                    <div class="about-feature-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <div>
                        <h4>Lokasi Strategis</h4>
                        <p>Dekat pusat kota Karimunjawa, akses mudah ke pelabuhan, pasar, dan semua destinasi wisata utama.</p>
                    </div>
                </div>
                <div class="about-feature-item fade-in">
                    <div class="about-feature-icon"><i class="fas fa-mountain"></i></div>
                    <div>
                        <h4>Pemandangan Gunung & Sunset</h4>
                        <p>Bangunan unik dengan view gunung yang memukau dan dekat spot sunset terbaik di pulau.</p>
                    </div>
                </div>
                <div class="about-feature-item fade-in">
                    <div class="about-feature-icon"><i class="fas fa-motorcycle"></i></div>
                    <div>
                        <h4>Rental Motor</h4>
                        <p>Jelajahi Karimunjawa dengan mudah — sewa motor langsung di hotel, siap pakai kapan saja.</p>
                    </div>
                </div>
                <div class="about-feature-item fade-in">
                    <div class="about-feature-icon"><i class="fas fa-ship"></i></div>
                    <div>
                        <h4>Trip Laut & Snorkeling</h4>
                        <p>Paket wisata laut lengkap — island hopping, snorkeling di terumbu karang, dan pantai tersembunyi.</p>
                    </div>
                </div>
                <div class="about-feature-item fade-in">
                    <div class="about-feature-icon"><i class="fas fa-utensils"></i></div>
                    <div>
                        <h4>Hotel & Resto</h4>
                        <p>Kamar nyaman dengan restoran in-house menyajikan seafood segar dan masakan lokal autentik.</p>
                    </div>
                </div>
                <div class="about-feature-item fade-in">
                    <div class="about-feature-icon"><i class="fas fa-spa"></i></div>
                    <div>
                        <h4>Tenang & Nyaman</h4>
                        <p>Arsitektur unik yang didesain untuk ketenangan, dikelilingi alam tropis yang asri dan segar.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

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
                $icon = $roomIcons[trim($room['type_name'])] ?? '🏨';
                $avail = (int)$room['available_rooms'];
                $total = (int)$room['total_rooms'];
                
                if ($avail >= 3) { $ac = 'available'; $at = $avail . ' Available'; }
                elseif ($avail > 0) { $ac = 'limited'; $at = 'Only ' . $avail . ' Left'; }
                else { $ac = 'full'; $at = 'Fully Booked'; }
            ?>
            <?php
                $typeName = trim($room['type_name']);
                $gallery = $roomGalleries[$typeName] ?? [];
                $primary = $roomPrimary[$typeName] ?? '';
                // If primary is set, put it first
                if ($primary && in_array($primary, $gallery)) {
                    $gallery = array_values(array_diff($gallery, [$primary]));
                    array_unshift($gallery, $primary);
                }
            ?>
            <div class="room-card fade-in">
                <div class="room-card-image <?= count($gallery) > 1 ? 'has-gallery' : '' ?>" data-total="<?= count($gallery) ?>">
                    <span class="room-type-badge"><?= htmlspecialchars($typeName) ?></span>
                    <?php if (!empty($gallery)): ?>
                        <?php foreach ($gallery as $gi => $gImg): ?>
                        <div class="room-slide <?= $gi === 0 ? 'active' : '' ?>" style="background-image: url('<?= BASE_URL ?>/<?= htmlspecialchars($gImg) ?>');"></div>
                        <?php endforeach; ?>
                        <?php if (count($gallery) > 1): ?>
                        <button class="room-nav room-nav-prev" onclick="slideRoom(this,-1)"><i class="fas fa-chevron-left"></i></button>
                        <button class="room-nav room-nav-next" onclick="slideRoom(this,1)"><i class="fas fa-chevron-right"></i></button>
                        <div class="room-dots">
                            <?php for ($di = 0; $di < count($gallery); $di++): ?>
                            <span class="room-dot <?= $di === 0 ? 'active' : '' ?>" onclick="goSlide(this,<?= $di ?>)"></span>
                            <?php endfor; ?>
                        </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="room-visual"><?= $icon ?></div>
                    <?php endif; ?>
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
                    <div class="room-book-btn">
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

<script>
// Room gallery slider
function slideRoom(btn, dir) {
    const container = btn.closest('.room-card-image');
    const slides = container.querySelectorAll('.room-slide');
    const dots = container.querySelectorAll('.room-dot');
    let current = [...slides].findIndex(s => s.classList.contains('active'));
    slides[current].classList.remove('active');
    if (dots[current]) dots[current].classList.remove('active');
    current = (current + dir + slides.length) % slides.length;
    slides[current].classList.add('active');
    if (dots[current]) dots[current].classList.add('active');
}
function goSlide(dot, idx) {
    const container = dot.closest('.room-card-image');
    const slides = container.querySelectorAll('.room-slide');
    const dots = container.querySelectorAll('.room-dot');
    slides.forEach(s => s.classList.remove('active'));
    dots.forEach(d => d.classList.remove('active'));
    slides[idx].classList.add('active');
    dots[idx].classList.add('active');
}

// Touch swipe support for room gallery
document.querySelectorAll('.room-card-image.has-gallery').forEach(card => {
    let startX = 0, startY = 0, distX = 0;
    card.addEventListener('touchstart', e => {
        startX = e.touches[0].clientX;
        startY = e.touches[0].clientY;
    }, { passive: true });
    card.addEventListener('touchmove', e => {
        distX = e.touches[0].clientX - startX;
    }, { passive: true });
    card.addEventListener('touchend', () => {
        if (Math.abs(distX) > 40) {
            const slides = card.querySelectorAll('.room-slide');
            const dots = card.querySelectorAll('.room-dot');
            let current = [...slides].findIndex(s => s.classList.contains('active'));
            slides[current].classList.remove('active');
            if (dots[current]) dots[current].classList.remove('active');
            current = (current + (distX < 0 ? 1 : -1) + slides.length) % slides.length;
            slides[current].classList.add('active');
            if (dots[current]) dots[current].classList.add('active');
        }
        distX = 0;
    }, { passive: true });
});
</script>
