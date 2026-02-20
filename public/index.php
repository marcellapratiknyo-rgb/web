<?php
/**
 * NARAYANA KARIMUNJAWA - Homepage
 */

require_once './includes/config.php' ?? die('Config not found');

$pageTitle = 'Home';
$additionalCSS = [];
$additionalJS = [];

?>
<?php include './includes/header.php'; ?>

<section class="section" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 4rem 0;">
    <div class="container">
        <div style="max-width: 600px;">
            <h1 style="font-size: 3rem; margin-bottom: 1rem; font-weight: bold;">Welcome to Narayana Karimunjawa</h1>
            <p style="font-size: 1.25rem; margin-bottom: 2rem; opacity: 0.9;">Experience paradise on earth. A premium beach resort offering luxury accommodations and world-class services in the heart of Karimunjawa Islands.</p>
            <a href="<?= BASE_URL ?>/booking.php" class="btn btn-primary" style="background: white; color: #667eea; font-weight: 700; padding: 1rem 2rem;">Book Your Stay Now →</a>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <h2 style="text-align: center; margin-bottom: 3rem; font-size: 2rem; font-weight: bold;">Our Room Types</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem;">
            <div class="card" style="text-align: center;">
                <h3 style="margin-bottom: 0.5rem;">Standard Room</h3>
                <p style="color: #6b7280; margin-bottom: 1rem;">Comfortable room with basic amenities</p>
                <p style="font-size: 1.5rem; color: #667eea; font-weight: bold; margin-bottom: 1rem;">Rp 450.000/night</p>
                <a href="<?= BASE_URL ?>/rooms.php" class="btn btn-primary">View Details</a>
            </div>
            
            <div class="card" style="text-align: center;">
                <h3 style="margin-bottom: 0.5rem;">Deluxe Room</h3>
                <p style="color: #6b7280; margin-bottom: 1rem;">Spacious room with ocean view</p>
                <p style="font-size: 1.5rem; color: #667eea; font-weight: bold; margin-bottom: 1rem;">Rp 650.000/night</p>
                <a href="<?= BASE_URL ?>/rooms.php" class="btn btn-primary">View Details</a>
            </div>
            
            <div class="card" style="text-align: center;">
                <h3 style="margin-bottom: 0.5rem;">Suite</h3>
                <p style="color: #6b7280; margin-bottom: 1rem;">Premium suite with living area</p>
                <p style="font-size: 1.5rem; color: #667eea; font-weight: bold; margin-bottom: 1rem;">Rp 950.000/night</p>
                <a href="<?= BASE_URL ?>/rooms.php" class="btn btn-primary">View Details</a>
            </div>
            
            <div class="card" style="text-align: center;">
                <h3 style="margin-bottom: 0.5rem;">Villa</h3>
                <p style="color: #6b7280; margin-bottom: 1rem;">Private villa with private pool</p>
                <p style="font-size: 1.5rem; color: #667eea; font-weight: bold; margin-bottom: 1rem;">Rp 1.500.000/night</p>
                <a href="<?= BASE_URL ?>/rooms.php" class="btn btn-primary">View Details</a>
            </div>
        </div>
    </div>
</section>

<section class="section" style="background: #f3f4f6;">
    <div class="container">
        <h2 style="text-align: center; margin-bottom: 3rem; font-size: 2rem; font-weight: bold;">Why Choose Us?</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem;">
            <div style="text-align: center;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">🏖️</div>
                <h3 style="margin-bottom: 0.5rem;">Beach Location</h3>
                <p style="color: #6b7280;">Located directly on the beach with stunning ocean views and white sandy shores.</p>
            </div>
            
            <div style="text-align: center;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">⭐</div>
                <h3 style="margin-bottom: 0.5rem;">Premium Service</h3>
                <p style="color: #6b7280;">24/7 customer service with multilingual staff ready to assist you.</p>
            </div>
            
            <div style="text-align: center;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">💰</div>
                <h3 style="margin-bottom: 0.5rem;">Best Rates</h3>
                <p style="color: #6b7280;">Competitive prices with flexible booking options and special discounts.</p>
            </div>
            
            <div style="text-align: center;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">🎉</div>
                <h3 style="margin-bottom: 0.5rem;">Activities</h3>
                <p style="color: #6b7280;">Water sports, island hopping, and exciting adventure packages available.</p>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container" style="text-align: center;">
        <h2 style="margin-bottom: 2rem; font-size: 2rem; font-weight: bold;">Ready to Book Your Paradise?</h2>
        <p style="font-size: 1.1rem; margin-bottom: 2rem; color: #6b7280;">Find the perfect room for your stay and create unforgettable memories in Karimunjawa.</p>
        <a href="<?= BASE_URL ?>/booking.php" class="btn btn-primary" style="padding: 1rem 3rem; font-size: 1.1rem;">Start Booking Now →</a>
    </div>
</section>

<?php include './includes/footer.php'; ?>
