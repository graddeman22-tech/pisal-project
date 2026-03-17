# Pisal Masala - E-commerce Website

A complete PHP e-commerce website for selling premium Indian spices and masalas.

## Project Structure

```
pisal-masala/
├── index.php                 # Main homepage
├── includes/
│   ├── db.php               # Database connection and utilities
│   ├── header.php           # Website header with navigation
│   └── footer.php           # Website footer
├── assets/
│   ├── css/
│   │   └── style.css        # Custom CSS styles
│   ├── js/                  # JavaScript files
│   └── images/
│       ├── products/        # Product images
│       ├── categories/      # Category images
│       ├── customers/       # Customer testimonials
│       └── banners/         # Hero banners
├── products/                # Product-related pages
├── admin/                   # Admin panel
└── database/                # Database files
```

## Features

### Frontend
- **Responsive Design**: Mobile-first approach using Tailwind CSS
- **Product Catalog**: Browse products by category
- **Search Functionality**: Search products by name or category
- **Shopping Cart**: Add/remove items, quantity management
- **User Accounts**: Registration, login, profile management
- **Wishlist**: Save favorite products
- **Reviews & Ratings**: Customer feedback system
- **Newsletter**: Email subscription
- **Payment Integration**: Multiple payment options

### Backend
- **Admin Panel**: Complete product and order management
- **Database**: MySQL with secure connections
- **Security**: Input sanitization, SQL injection prevention
- **Session Management**: Secure user sessions
- **Error Handling**: Comprehensive error management

## Installation

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)

### Setup Steps

1. **Clone/Download the project**
   ```bash
   git clone <repository-url>
   cd pisal-masala
   ```

2. **Database Setup**
   - Create a database named `pisal_masala`
   - Import the database schema (provided separately)
   - Update database credentials in `includes/db.php`

3. **Configure Web Server**
   - Point document root to the project directory
   - Ensure mod_rewrite is enabled for clean URLs

4. **File Permissions**
   ```bash
   chmod 755 -R .
   chmod 777 assets/images/
   ```

## Database Configuration

Update the database credentials in `includes/db.php`:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_NAME', 'pisal_masala');
```

## Key Files Description

### `includes/db.php`
- Database connection handler
- Security functions (sanitize input, prepared statements)
- Session management
- Helper functions for database operations

### `includes/header.php`
- HTML head with meta tags and CSS
- Navigation menu with dropdown
- Search functionality
- User account links
- Shopping cart counter
- Flash messages

### `includes/footer.php`
- Newsletter subscription
- Footer links and information
- Social media integration
- Payment methods display
- Back to top button
- JavaScript functions

### `index.php`
- Hero section with call-to-action
- Featured products showcase
- Category browsing
- Customer testimonials
- Special offers banner

## CSS Framework

The website uses:
- **Tailwind CSS**: For utility-first styling
- **Font Awesome**: For icons
- **Custom CSS**: Additional animations and effects

## Security Features

- Input sanitization using `mysqli_real_escape_string()`
- Prepared statements to prevent SQL injection
- XSS protection with `htmlspecialchars()`
- Session security
- CSRF protection (to be implemented)

## Responsive Design

The website is fully responsive and works on:
- Desktop (1200px+)
- Tablet (768px - 1199px)
- Mobile (320px - 767px)

## Browser Support

- Chrome 60+
- Firefox 55+
- Safari 12+
- Edge 79+

## Performance Optimization

- Optimized images
- Minified CSS/JS
- Lazy loading for images
- Efficient database queries
- Caching strategies

## Future Enhancements

- Multi-language support
- Advanced filtering and sorting
- Product recommendations
- Inventory management
- Analytics integration
- Mobile app development

## Support

For support and inquiries:
- Email: info@pisalmasala.com
- Phone: +91 98765 43210

## License

This project is proprietary to Pisal Masala Brand.

---

**Note**: This is a basic e-commerce structure. Additional features and pages need to be developed based on specific business requirements.
