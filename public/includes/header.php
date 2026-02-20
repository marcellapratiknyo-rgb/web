<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= htmlspecialchars(SITE_DESCRIPTION) ?>">
    <title><?= htmlspecialchars(SITE_NAME . ' - ' . ($pageTitle ?? 'Book Your Stay')) ?></title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <?php if (!empty($additionalCSS)): ?>
        <?php foreach ($additionalCSS as $css): ?>
        <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/<?= htmlspecialchars($css) ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1e40af;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --dark: #1f2937;
            --light: #f3f4f6;
            --border: #e5e7eb;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            color: #374151;
            line-height: 1.6;
            background: #f9fafb;
        }
        
        .navbar {
            background: white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .navbar-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .navbar-logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--primary);
            text-decoration: none;
        }
        
        .navbar-menu {
            display: flex;
            gap: 2rem;
            list-style: none;
        }
        
        .navbar-menu a {
            text-decoration: none;
            color: #374151;
            font-weight: 500;
            transition: color 0.3s;
        }
        
        .navbar-menu a:hover {
            color: var(--primary);
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }
        
        .section {
            padding: 2rem 0;
        }
        
        .card {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
            font-size: 1rem;
        }
        
        .btn-primary {
            background: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }
        
        .btn-block {
            display: block;
            width: 100%;
            text-align: center;
        }
        
        .alert {
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1rem;
        }
        
        .alert-warning {
            background: #fef3c7;
            color: #92400e;
            border-left: 4px solid #f59e0b;
        }
        
        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }
        
        .alert-success {
            background: #dcfce7;
            color: #166534;
            border-left: 4px solid #10b981;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #374151;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--border);
            border-radius: 6px;
            font-family: inherit;
            font-size: 1rem;
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
        
        .footer {
            background: #1f2937;
            color: #f3f4f6;
            padding: 3rem 0 1rem;
            margin-top: 4rem;
        }
        
        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }
        
        .footer-section h3 {
            margin-bottom: 1rem;
            color: white;
        }
        
        .footer-section ul {
            list-style: none;
        }
        
        .footer-section ul li {
            margin-bottom: 0.5rem;
        }
        
        .footer-section a {
            color: #d1d5db;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .footer-section a:hover {
            color: white;
        }
        
        .footer-bottom {
            border-top: 1px solid #374151;
            padding-top: 2rem;
            text-align: center;
            color: #9ca3af;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-content">
            <a href="<?= BASE_URL ?>" class="navbar-logo">🏨 <?= htmlspecialchars(SITE_NAME) ?></a>
            <ul class="navbar-menu">
                <li><a href="<?= BASE_URL ?>">Home</a></li>
                <li><a href="<?= BASE_URL ?>/rooms.php">Rooms</a></li>
                <li><a href="<?= BASE_URL ?>/booking.php">Booking</a></li>
                <li><a href="<?= BASE_URL ?>/contact.php">Contact</a></li>
            </ul>
        </div>
    </nav>
    
    <!-- Content -->
