# Narayana Karimunjawa Hotel - Booking Engine

Professional hotel booking system for Narayana Karimunjawa Resort.

## ✅ HOMEPAGE IS NOW WORKING!

Fixed the .htaccess syntax error. Both the homepage and rooms page are loading correctly:
- **Homepage**: http://localhost:8081/narayanakarimunjawa/public/ ✅
- **Rooms Page**: http://localhost:8081/narayanakarimunjawa/public/rooms.php ✅
- **Test Script**: http://localhost:8081/narayanakarimunjawa/test-setup.php ✅

### What Was Fixed
The .htaccess file had invalid Apache syntax with XML-style tags:
- ❌ **Wrong**: `<RewriteEngine On>` 
- ✅ **Fixed**: `RewriteEngine On`
- Removed invalid `<Files>` and `<FilesMatch>` blocks

## Project Structure

```
/narayanakarimunjawa/
├── config/
│   └── config.php              # Database & environment configuration
├── public/
│   ├── index.php              # Homepage ✅ WORKING
│   ├── rooms.php              # Room types showcase ✅ WORKING
│   ├── booking.php            # Booking interface (to be created)
│   ├── confirmation.php       # Booking confirmation (to be created)
│   ├── contact.php            # Contact form (to be created)
│   ├── includes/
│   │   ├── header.php         # Navigation & head
│   │   └── footer.php         # Footer & scripts
│   ├── assets/
│   │   ├── css/
│   │   ├── js/
│   │   └── images/
│   ├── .htaccess              # URL rewriting ✅ FIXED
│   └── test-setup.php         # Setup verification script
├── api/
│   ├── get-available-rooms.php (to be created)
│   ├── create-booking.php (to be created)
│   ├── payment-process.php (to be created)
│   └── send-email.php (to be created)
├── database-schema.sql        # Full database schema
├── database-setup.php         # Setup script
└── README.md                  # This file
```

## Database

**Development**: `adf_web_narayana`
**Production**: `adfb2574_web_narayana`

### Tables (10 tables, fully normalized)
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

## Quick Start

### Local Testing (XAMPP)
1. Ensure MySQL and Apache are running
2. Database `adf_web_narayana` is already created
3. Open: http://localhost:8081/narayanakarimunjawa/public/
4. Test rooms page: http://localhost:8081/narayanakarimunjawa/public/rooms.php

### Production Deployment (Niagahoster)
1. Upload `/narayanakarimunjawa/` folder via FTP
2. Create database: `adfb2574_web_narayana`
3. Import schema: `database-schema.sql`
4. Update `config/config.php` with production credentials
5. Set proper permissions: `chmod 755 public/`
6. Domain: https://narayanakarimunjawa.com/

## Project Status

### Phase 1: Foundation ✅ COMPLETE
- [x] Database (10 tables with sample data)
- [x] Homepage ✅ WORKING
- [x] Rooms showcase ✅ WORKING
- [x] Configuration (auto-detects local/production)
- [x] URL rewriting (.htaccess fixed!)
- [x] Error handling & logging
- [x] Git repository with commits

### Phase 2: Booking System 🔄 NEXT
- [ ] booking.php - Interactive booking form
- [ ] confirmation.php - Booking confirmation
- [ ] contact.php - Contact form
- [ ] Database inserts for bookings

### Phase 3: API Endpoints 🔄 AFTER
- [ ] api/get-available-rooms.php
- [ ] api/create-booking.php
- [ ] api/payment-process.php
- [ ] api/send-email.php

### Phase 4: Payments & Email 🔄 LATER
- [ ] Midtrans integration
- [ ] Email notifications
- [ ] Payment tracking

## Technologies

- **Backend**: PHP 8.2.30 + PDO
- **Database**: MySQL/MariaDB (utf8mb4)
- **Frontend**: HTML5, CSS3, Vanilla JavaScript ES6+
- **Payment**: Midtrans (ready)
- **Icons**: Font Awesome 6.4.0 + Feather Icons

## Recent Changes

**Commit d8908d4** ✅: Fixed .htaccess syntax error
- Removed XML-style tags from Apache directives
- Homepage and rooms page now loading correctly!

**Commit fcc42eb**: Initial project setup
- Database schema with 10 tables
- Project structure & configuration

**Commit cf27882**: English translation  
- Owner module in English

## What's Working Now ✅

1. ✅ Homepage loads with hero section and room cards
2. ✅ Rooms page displays room types from database
3. ✅ Database connection working properly
4. ✅ Navigation header and footer templates
5. ✅ Configuration auto-detects local vs production
6. ✅ Error handling and logging system

## Next Steps

When ready, build these components in order:
1. **booking.php** - Interactive form for reservations
2. **API endpoints** - Backend services for bookings
3. **Payment integration** - Midtrans setup
4. **Email system** - Confirmation notifications
5. **Admin dashboard** - Management interface

## Troubleshooting

### Internal Server Error (500) - NOW FIXED ✅
**Solution**: Fixed .htaccess syntax (removed XML-style tags)
- ErrorLog showed: `<RewriteEngine> was not closed`
- Changed `<RewriteEngine On>` to `RewriteEngine On`

### Database Connection Issues
- Verify MySQL is running in XAMPP
- Check `config/config.php` has correct credentials
- Run test script: http://localhost:8081/narayanakarimunjawa/test-setup.php

### File Not Found
- All paths use absolute paths with `__DIR__`
- No relative path issues
- Check file permissions if needed

## Support

**Hotel Contact:**
- Email: narayanahotelkarimunjawa@gmail.com
- Phone: +62-812-2222-8590
- Website: https://narayanakarimunjawa.com

---

**Project**: Narayana Karimunjawa Hotel Booking Engine  
**Status**: ✅ Phase 1 Complete (Homepage Working!)  
**Latest**: Commit d8908d4 - .htaccess fixed  
**Local URL**: http://localhost:8081/narayanakarimunjawa/public/  
**Created**: February 2026

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

## Project Status

### ✅ Completed
- [x] Database schema (10 tables, fully normalized)
- [x] Environment-aware configuration
- [x] Homepage (index.php) with hero section
- [x] Room types showcase (rooms.php with database-driven content)
- [x] Navigation templates (header, footer with utilities)
- [x] .htaccess URL rewriting
- [x] PDO database abstraction layer
- [x] Helper functions (formatting, API, validation)
- [x] Error handling & logging
- [x] Git repository initialization

### 🔄 In Progress / To Do
- [ ] `booking.php` - Full booking interface
- [ ] `confirmation.php` - Booking confirmation page
- [ ] `contact.php` - Contact & inquiry form
- [ ] API endpoints:
  - [ ] `api/get-available-rooms.php`
  - [ ] `api/create-booking.php`
  - [ ] `api/payment-process.php`
  - [ ] `api/send-email.php`
- [ ] Midtrans payment gateway integration
- [ ] Email notification system
- [ ] Admin dashboard (separate)
- [ ] Guest review system frontend
- [ ] Responsive design testing

### Latest Changes
- **Commit d8908d4**: Fixed .htaccess syntax error (removed XML tags) and improved path handling
- **Commit fcc42eb**: Initial setup with database schema and project structure
- **Commit cf27882**: Owner module English translation (from adf_system)

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
