# Narayana Karimunjawa Hotel - Booking Engine

Professional hotel booking system for Narayana Karimunjawa Resort.

## Project Structure

```
/narayanakarimunjawa/
├── config/
│   └── config.php              # Database & environment configuration
├── public/
│   ├── index.php              # Homepage
│   ├── rooms.php              # Room types showcase
│   ├── booking.php            # Booking interface
│   ├── confirmation.php       # Booking confirmation
│   ├── contact.php            # Contact form
│   ├── includes/
│   │   ├── header.php         # Navigation & head
│   │   └── footer.php         # Footer & scripts
│   ├── assets/
│   │   ├── css/
│   │   ├── js/
│   │   └── images/
│   └── .htaccess              # URL rewriting
├── api/
│   ├── get-available-rooms.php
│   ├── create-booking.php
│   ├── payment-process.php
│   └── send-email.php
├── database-schema.sql        # Full database schema
└── database-setup.php         # Setup script
```

## Database

**Development**: `adf_web_narayana`
**Production**: `adfb2574_web_narayana`

### Tables
- `room_types` - Room categories and pricing
- `rooms` - Individual rooms
- `room_availability` - Availability tracking
- `guests` - Guest information
- `bookings` - Reservations
- `payments` - Payment records
- `reviews` - Guest reviews
- `settings` - Site configuration
- `email_templates` - Email templates
- `promo_codes` - Discount codes

## Features

✅ **Room Management**
- Multiple room types (Standard, Deluxe, Suite, Villa)
- Amenities list
- Dynamic pricing
- Availability tracking

✅ **Booking System**
- Date selection
- Guest information collection
- Real-time availability check
- Instant confirmation

✅ **Payment Integration**
- Midtrans gateway ready
- Multiple payment methods
- Transaction tracking

✅ **Guest Features**
- Review & rating system
- Email confirmations
- Promo code support

✅ **Admin Features** (coming soon)
- Booking management dashboard
- Payment tracking
- Guest management
- Report generation

## Setup Instructions

### Local Development (XAMPP)

1. **Database Setup**
   ```bash
   php database-setup.php
   ```

2. **Update Config** (if needed)
   ```php
   // config/config.php
   define('DB_USER', 'root');
   define('DB_PASS', '');
   ```

3. **Access**
   ```
   http://localhost/narayanakarimunjawa/public/
   ```

### Production (Niagahoster)

1. **Upload Files** via FTP/SFTP

2. **Create Database**
   - Use cPanel MySQL Wizard
   - Database: `adfb2574_web_narayana`

3. **Import Schema**
   ```bash
   mysql -u user -p adfb2574_web_narayana < database-schema.sql
   ```

4. **Update Config**
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'adfb2574_narayana');
   define('DB_PASS', 'your_password');
   define('DB_NAME', 'adfb2574_web_narayana');
   ```

5. **Set Permissions**
   ```bash
   chmod 755 public/
   chmod 644 public/*.php
   ```

6. **Access**
   ```
   https://narayanakarimunjawa.com/
   ```

## Configuration

### Environment Detection
- Local: Detects `localhost` or `127.0.0.1`
- Production: Everything else

### Payment Gateway
Edit `config/config.php`:
```php
define('MIDTRANS_MERCHANT_ID', 'your_merchant_id');
define('MIDTRANS_CLIENT_KEY', 'your_client_key');
define('MIDTRANS_SERVER_KEY', 'your_server_key');
```

### Email Configuration
```php
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_USER', 'your-email@gmail.com');
define('SMTP_PASS', 'your-app-password');
```

## API Endpoints

### Public API
- `GET /api/get-available-rooms.php?check_in=YYYY-MM-DD&check_out=YYYY-MM-DD&guests=N`
- `POST /api/create-booking.php`
- `POST /api/payment-process.php`
- `POST /api/send-email.php`

## Technologies

- **Backend**: PHP 8.2+
- **Database**: MySQL/MariaDB
- **Frontend**: HTML5, CSS3, Vanilla JavaScript
- **Payment**: Midtrans
- **Charts**: Chart.js
- **Icons**: Font Awesome, Feather Icons

## Development

### Pages to Create
- [ ] `booking.php` - Full booking interface
- [ ] `confirmation.php` - Booking confirmation page
- [ ] `contact.php` - Contact & inquiry form
- [ ] Admin dashboard (separate)

### API Endpoints to Create
- [ ] `api/get-available-rooms.php`
- [ ] `api/create-booking.php`
- [ ] `api/payment-process.php`
- [ ] `api/send-email.php`

## Security

✅ Implemented:
- PDO prepared statements (SQL injection protection)
- XSS prevention
- CSRF tokens (to be added)
- Password hashing (for future admin)
- SSL/HTTPS support

## Support

For issues or questions:
- Email: narayanahotelkarimunjawa@gmail.com
- Phone: +62-812-2222-8590

---

**Created**: February 2026
**Status**: In Development
**Domain**: narayanakarimunjawa.com
