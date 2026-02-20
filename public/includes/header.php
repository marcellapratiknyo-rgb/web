<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= SITE_DESCRIPTION ?>">
    <meta name="keywords" content="Karimunjawa hotel, island resort, luxury accommodation, Narayana Karimunjawa">
    <title><?= isset($pageTitle) ? $pageTitle . ' — ' . SITE_NAME : SITE_NAME . ' — ' . SITE_TAGLINE ?></title>

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
            <div class="brand-logo">Narayana</div>
            <div class="brand-sub">Karimunjawa</div>
        </a>
        <ul class="nav-links" id="navLinks">
            <li><a href="<?= BASE_URL ?>/" class="<?= ($currentPage ?? '') === 'home' ? 'active' : '' ?>">Home</a></li>
            <li><a href="<?= BASE_URL ?>/rooms.php" class="<?= ($currentPage ?? '') === 'rooms' ? 'active' : '' ?>">Rooms</a></li>
            <li><a href="<?= BASE_URL ?>/booking.php" class="<?= ($currentPage ?? '') === 'booking' ? 'active' : '' ?>">Reservations</a></li>
            <li><a href="<?= BASE_URL ?>/contact.php" class="<?= ($currentPage ?? '') === 'contact' ? 'active' : '' ?>">Contact</a></li>
            <li><a href="<?= BASE_URL ?>/booking.php" class="nav-book-btn">Book Now</a></li>
        </ul>
        <button class="nav-toggle" id="navToggle" aria-label="Toggle navigation">
            <span></span><span></span><span></span>
        </button>
    </div>
</nav>
