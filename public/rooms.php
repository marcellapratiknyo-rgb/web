<?php
/**
 * NARAYANA KARIMUNJAWA - Room Types Page
 */

require_once './includes/config.php';

$pageTitle = 'Our Rooms';
$additionalCSS = [];
$additionalJS = [];

// Get room types
try {
    $roomTypes = dbFetchAll("SELECT * FROM room_types ORDER BY base_price ASC");
} catch (Exception $e) {
    if (DEBUG_MODE) {
        $roomTypes = [];
    }
}

?>
<?php include './includes/header.php'; ?>

<section class="section" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 3rem 0;">
    <div class="container">
        <h1 style="font-size: 2.5rem; margin-bottom: 0.5rem; font-weight: bold;">Our Rooms</h1>
        <p style="opacity: 0.9;">Discover our carefully designed rooms and suites</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <?php if (empty($roomTypes)): ?>
        <div class="alert alert-warning">
            <strong>Rooms data loading...</strong> Please check back soon.
        </div>
        <?php else: ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 2rem;">
            <?php foreach ($roomTypes as $room): ?>
            <div class="card">
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); height: 200px; border-radius: 8px; margin: -1.5rem -1.5rem 1rem -1.5rem; display: flex; align-items: center; justify-content: center; color: white; font-size: 3rem;">
                    🏨
                </div>
                <h2 style="margin-bottom: 0.5rem; color: #667eea;"><?= htmlspecialchars($room['type_name']) ?></h2>
                <p style="color: #6b7280; margin-bottom: 1rem;"><?= htmlspecialchars($room['description'] ?? '') ?></p>
                
                <div style="background: #f3f4f6; padding: 1rem; border-radius: 6px; margin-bottom: 1rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <p style="color: #6b7280; font-size: 0.9rem;">Base Price</p>
                            <p style="font-size: 1.5rem; font-weight: bold; color: #667eea;">Rp <?= number_format($room['base_price'], 0, ',', '.') ?></p>
                            <p style="color: #6b7280; font-size: 0.9rem;">per night</p>
                        </div>
                        <div style="text-align: right;">
                            <p style="color: #6b7280; font-size: 0.9rem;">Capacity</p>
                            <p style="font-size: 1.5rem; font-weight: bold;">👥 <?= intval($room['max_guests']) ?></p>
                        </div>
                    </div>
                </div>
                
                <?php if ($room['amenities']): ?>
                <div style="margin-bottom: 1rem;">
                    <p style="font-weight: 600; margin-bottom: 0.5rem;">Amenities:</p>
                    <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                        <?php 
                        $amenities = json_decode($room['amenities'] ?? '[]', true);
                        foreach ($amenities as $amenity):
                        ?>
                        <span style="background: #e0e7ff; color: #667eea; padding: 0.25rem 0.75rem; border-radius: 4px; font-size: 0.9rem;">
                            ✓ <?= htmlspecialchars($amenity) ?>
                        </span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <a href="<?= BASE_URL ?>/booking.php" class="btn btn-primary btn-block">Book This Room</a>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<section class="section" style="background: #f3f4f6;">
    <div class="container">
        <h2 style="text-align: center; margin-bottom: 2rem; font-size: 1.5rem; font-weight: bold;">Room Amenities</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
            <div style="text-align: center;">
                <p style="font-size: 2rem; margin-bottom: 0.5rem;">📶</p>
                <p style="font-weight: 600;">Free WiFi</p>
            </div>
            <div style="text-align: center;">
                <p style="font-size: 2rem; margin-bottom: 0.5rem;">❄️</p>
                <p style="font-weight: 600;">Air Conditioning</p>
            </div>
            <div style="text-align: center;">
                <p style="font-size: 2rem; margin-bottom: 0.5rem;">🚿</p>
                <p style="font-weight: 600;">Hot Water</p>
            </div>
            <div style="text-align: center;">
                <p style="font-size: 2rem; margin-bottom: 0.5rem;">📺</p>
                <p style="font-weight: 600;">Flat Screen TV</p>
            </div>
            <div style="text-align: center;">
                <p style="font-size: 2rem; margin-bottom: 0.5rem;">🛗</p>
                <p style="font-weight: 600;">24-Hour Service</p>
            </div>
            <div style="text-align: center;">
                <p style="font-size: 2rem; margin-bottom: 0.5rem;">🎧</p>
                <p style="font-weight: 600;">Sound System</p>
            </div>
        </div>
    </div>
</section>

<?php include './includes/footer.php'; ?>
