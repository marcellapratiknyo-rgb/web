<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= SITE_DESCRIPTION ?>">
    <meta name="keywords" content="Karimunjawa hotel, island resort, luxury accommodation, Narayana Karimunjawa">
    <title><?= isset($pageTitle) ? $pageTitle . ' — ' . SITE_NAME : SITE_NAME . ' — ' . SITE_TAGLINE ?></title>

    <?php
    // Load favicon from database
    $faviconPath = '';
    $logoPath = '';
    try {
        $brandRows = dbFetchAll("SELECT setting_key, setting_value FROM settings WHERE setting_key IN ('web_favicon', 'web_logo')");
        foreach ($brandRows as $br) {
            if ($br['setting_key'] === 'web_favicon' && !empty($br['setting_value'])) $faviconPath = $br['setting_value'];
            if ($br['setting_key'] === 'web_logo' && !empty($br['setting_value'])) $logoPath = $br['setting_value'];
        }
    } catch (Exception $e) {}
    if (!empty($faviconPath)):
        $ext = strtolower(pathinfo($faviconPath, PATHINFO_EXTENSION));
        $mimeMap = ['ico' => 'image/x-icon', 'png' => 'image/png', 'svg' => 'image/svg+xml', 'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'webp' => 'image/webp'];
        $mimeType = $mimeMap[$ext] ?? 'image/png';
    ?>
    <link rel="icon" type="<?= $mimeType ?>" href="<?= BASE_URL ?>/<?= htmlspecialchars($faviconPath) ?>">
    <link rel="shortcut icon" type="<?= $mimeType ?>" href="<?= BASE_URL ?>/<?= htmlspecialchars($faviconPath) ?>">
    <?php endif; ?>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,500;0,600;1,400;1,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>

<!-- Navigation -->
<nav class="navbar" id="mainNav">
    <div class="container">
        <a href="<?= BASE_URL ?>/" class="navbar-brand">
            <?php if (!empty($logoPath)): ?>
            <img src="<?= BASE_URL ?>/<?= htmlspecialchars($logoPath) ?>" alt="Narayana" class="brand-img">
            <?php endif; ?>
            <div class="brand-text">
                <div class="brand-logo">Narayana</div>
                <div class="brand-sub">Karimunjawa</div>
            </div>
        </a>
        <ul class="nav-links" id="navLinks">
            <li><a href="<?= BASE_URL ?>/" class="<?= ($currentPage ?? '') === 'home' ? 'active' : '' ?>">Home</a></li>
            <li><a href="<?= BASE_URL ?>/rooms.php" class="<?= ($currentPage ?? '') === 'rooms' ? 'active' : '' ?>">Rooms</a></li>
            <li><a href="<?= BASE_URL ?>/destinations.php" class="<?= ($currentPage ?? '') === 'destinations' ? 'active' : '' ?>">Destinations</a></li>
            <li><a href="<?= BASE_URL ?>/booking.php" class="<?= ($currentPage ?? '') === 'booking' ? 'active' : '' ?>">Reservations</a></li>
            <li><a href="<?= BASE_URL ?>/contact.php" class="<?= ($currentPage ?? '') === 'contact' ? 'active' : '' ?>">Contact</a></li>
            <li><a href="<?= BASE_URL ?>/booking.php" class="nav-book-btn">Book Now</a></li>
        </ul>
        <button class="nav-toggle" id="navToggle" aria-label="Toggle navigation">
            <span></span><span></span><span></span>
        </button>
    </div>
</nav>
