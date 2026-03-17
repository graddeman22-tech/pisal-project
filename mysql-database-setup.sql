-- PISAL E-commerce MySQL Database Setup
-- MySQL Database Schema

-- Create database (run this separately if needed)
-- CREATE DATABASE pisal_ecommerce;
-- USE pisal_ecommerce;

-- 1. Admins Table
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'super_admin') DEFAULT 'admin' NOT NULL,
    is_active BOOLEAN DEFAULT TRUE NOT NULL,
    last_login TIMESTAMP NULL,
    login_attempts INT DEFAULT 0 NOT NULL,
    locked_until TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL
);

-- 2. Users Table (Customers)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    phone VARCHAR(20),
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('customer', 'admin') DEFAULT 'customer' NOT NULL,
    is_active BOOLEAN DEFAULT TRUE NOT NULL,
    is_verified BOOLEAN DEFAULT FALSE NOT NULL,
    verification_token VARCHAR(255),
    reset_token VARCHAR(255),
    reset_token_expires TIMESTAMP NULL,
    last_login TIMESTAMP NULL,
    login_attempts INT DEFAULT 0 NOT NULL,
    locked_until TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL
);

-- 3. User Addresses
CREATE TABLE IF NOT EXISTS user_addresses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type VARCHAR(20) DEFAULT 'home' NOT NULL,
    is_default BOOLEAN DEFAULT FALSE NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    address_line1 VARCHAR(255) NOT NULL,
    address_line2 VARCHAR(255),
    city VARCHAR(100) NOT NULL,
    state VARCHAR(100) NOT NULL,
    pincode VARCHAR(10) NOT NULL,
    country VARCHAR(100) DEFAULT 'India' NOT NULL,
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 4. Categories
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) UNIQUE NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    description TEXT,
    image_url VARCHAR(500),
    parent_id INT,
    is_active BOOLEAN DEFAULT TRUE NOT NULL,
    sort_order INT DEFAULT 0 NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    FOREIGN KEY (parent_id) REFERENCES categories(id)
);

-- 5. Products
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT,
    short_description TEXT,
    sku VARCHAR(100) UNIQUE NOT NULL,
    barcode VARCHAR(100),
    category_id INT,
    price DECIMAL(10,2) NOT NULL,
    original_price DECIMAL(10,2),
    cost_price DECIMAL(10,2),
    weight DECIMAL(8,2),
    status ENUM('active', 'inactive', 'out_of_stock', 'draft') DEFAULT 'active' NOT NULL,
    featured BOOLEAN DEFAULT FALSE NOT NULL,
    track_inventory BOOLEAN DEFAULT TRUE NOT NULL,
    stock_quantity INT DEFAULT 0 NOT NULL,
    low_stock_threshold INT DEFAULT 10 NOT NULL,
    allow_backorder BOOLEAN DEFAULT FALSE NOT NULL,
    requires_shipping BOOLEAN DEFAULT TRUE NOT NULL,
    image VARCHAR(500),
    badge VARCHAR(50),
    badge_color VARCHAR(20) DEFAULT 'red',
    rating DECIMAL(3,2) DEFAULT 4.5,
    tags VARCHAR(500),
    meta_title VARCHAR(255),
    meta_description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- 6. Orders
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_number VARCHAR(50) UNIQUE NOT NULL,
    user_id INT,
    guest_email VARCHAR(255),
    status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending' NOT NULL,
    currency VARCHAR(3) DEFAULT 'INR' NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    tax_amount DECIMAL(10,2) DEFAULT 0 NOT NULL,
    shipping_amount DECIMAL(10,2) DEFAULT 0 NOT NULL,
    discount_amount DECIMAL(10,2) DEFAULT 0 NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    payment_method ENUM('razorpay', 'stripe', 'upi', 'credit_card', 'debit_card', 'cash_on_delivery'),
    payment_status ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending' NOT NULL,
    payment_id VARCHAR(255),
    transaction_id VARCHAR(255),
    notes TEXT,
    internal_notes TEXT,
    shipping_address TEXT NOT NULL,
    billing_address TEXT NOT NULL,
    tracking_number VARCHAR(255),
    shipped_at TIMESTAMP NULL,
    delivered_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- 7. Order Items
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    sku VARCHAR(100) NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- 8. Cart
CREATE TABLE IF NOT EXISTS cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    session_id VARCHAR(255),
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- 9. Wishlist
CREATE TABLE IF NOT EXISTS wishlist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY unique_wishlist (user_id, product_id)
);

-- 10. Reviews
CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    user_id INT,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    title VARCHAR(255),
    content TEXT,
    is_verified_purchase BOOLEAN DEFAULT FALSE NOT NULL,
    is_approved BOOLEAN DEFAULT FALSE NOT NULL,
    helpful_count INT DEFAULT 0 NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- 11. Settings
CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    `key` VARCHAR(100) UNIQUE NOT NULL,
    value TEXT,
    description TEXT,
    type VARCHAR(20) DEFAULT 'string' NOT NULL,
    is_public BOOLEAN DEFAULT FALSE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL
);

-- Create indexes for better performance
CREATE INDEX IF NOT EXISTS idx_users_email ON users(email);
CREATE INDEX IF NOT EXISTS idx_orders_user_id ON orders(user_id);
CREATE INDEX IF NOT EXISTS idx_orders_status ON orders(status);
CREATE INDEX IF NOT EXISTS idx_products_status ON products(status);
CREATE INDEX IF NOT EXISTS idx_products_category ON products(category_id);
CREATE INDEX IF NOT EXISTS idx_products_featured ON products(featured);
CREATE INDEX IF NOT EXISTS idx_order_items_order_id ON order_items(order_id);
CREATE INDEX IF NOT EXISTS idx_order_items_product_id ON order_items(product_id);
CREATE INDEX IF NOT EXISTS idx_reviews_product_id ON reviews(product_id);
CREATE INDEX IF NOT EXISTS idx_reviews_user_id ON reviews(user_id);

-- Insert default admin user
INSERT INTO admins (username, email, password_hash, role) VALUES 
('admin', 'admin@pisal.in', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'super_admin');

-- Insert default categories
INSERT INTO categories (name, slug, description, sort_order) VALUES 
('Whole Spices', 'whole-spices', 'Premium whole spices from the best farms', 1),
('Ground Spices', 'ground-spices', 'Finely ground spices for perfect flavor', 2),
('Blended Masala', 'blended-masala', 'Traditional spice blends for authentic taste', 3),
('Combo Packs', 'combo-packs', 'Value packs with multiple spices', 4);

-- Insert sample products
INSERT INTO products (name, slug, sku, description, short_description, price, original_price, category_id, stock_quantity, featured, image, badge, badge_color, rating) VALUES 
('Premium Turmeric Powder', 'turmeric-powder', 'TUR-100G', 'Pure, organic turmeric powder with high curcumin content. Sourced from the best farms and processed under strict quality control.', 'Premium quality turmeric powder', 120.00, 150.00, (SELECT id FROM categories WHERE slug = 'ground-spices'), 150, TRUE, 'turmeric-powder.jpg', 'Bestseller', 'red', 4.5),
('Authentic Garam Masala', 'garam-masala', 'GAR-100G', 'Traditional blend of aromatic spices for perfect curries. A carefully crafted mix of premium spices.', 'Traditional garam masala blend', 180.00, 220.00, (SELECT id FROM categories WHERE slug = 'blended-masala'), 100, TRUE, 'garam-masala.jpg', 'Organic', 'green', 5.0),
('Kashmiri Red Chilli Powder', 'red-chilli-powder', 'CHL-100G', 'Mild yet flavorful red chilli powder with vibrant color. Perfect for adding color and taste to your dishes.', 'Premium red chilli powder', 95.00, 120.00, (SELECT id FROM categories WHERE slug = 'ground-spices'), 200, FALSE, 'red-chilli.jpg', 'Hot', 'orange', 4.0),
('Premium Cumin Seeds', 'cumin-seeds', 'CUM-100G', 'Aromatic and flavorful whole cumin seeds. Essential for Indian cooking with their distinctive warm flavor.', 'Aromatic cumin seeds', 160.00, 200.00, (SELECT id FROM categories WHERE slug = 'whole-spices'), 120, TRUE, 'jeera.jpg', 'Premium', 'purple', 4.7);

-- Insert default settings
INSERT INTO settings (`key`, value, description, type, is_public) VALUES 
('store_name', 'Pisal Masala', 'Store name', 'string', TRUE),
('store_email', 'info@pisalmasala.com', 'Store email', 'string', TRUE),
('store_phone', '+91 98765 43210', 'Store phone', 'string', TRUE),
('currency', 'INR', 'Default currency', 'string', TRUE),
('tax_rate', '18', 'Tax rate percentage', 'number', FALSE),
('shipping_cost', '50', 'Default shipping cost', 'number', FALSE),
('free_shipping_threshold', '499', 'Free shipping minimum amount', 'number', TRUE);

-- Create trigger for updated_at
DELIMITER //
CREATE TRIGGER update_admins_updated_at BEFORE UPDATE ON admins FOR EACH ROW
BEGIN
    SET NEW.updated_at = CURRENT_TIMESTAMP;
END//
CREATE TRIGGER update_users_updated_at BEFORE UPDATE ON users FOR EACH ROW
BEGIN
    SET NEW.updated_at = CURRENT_TIMESTAMP;
END//
CREATE TRIGGER update_user_addresses_updated_at BEFORE UPDATE ON user_addresses FOR EACH ROW
BEGIN
    SET NEW.updated_at = CURRENT_TIMESTAMP;
END//
CREATE TRIGGER update_categories_updated_at BEFORE UPDATE ON categories FOR EACH ROW
BEGIN
    SET NEW.updated_at = CURRENT_TIMESTAMP;
END//
CREATE TRIGGER update_products_updated_at BEFORE UPDATE ON products FOR EACH ROW
BEGIN
    SET NEW.updated_at = CURRENT_TIMESTAMP;
END//
CREATE TRIGGER update_orders_updated_at BEFORE UPDATE ON orders FOR EACH ROW
BEGIN
    SET NEW.updated_at = CURRENT_TIMESTAMP;
END//
CREATE TRIGGER update_order_items_updated_at BEFORE UPDATE ON order_items FOR EACH ROW
BEGIN
    SET NEW.updated_at = CURRENT_TIMESTAMP;
END//
CREATE TRIGGER update_cart_updated_at BEFORE UPDATE ON cart FOR EACH ROW
BEGIN
    SET NEW.updated_at = CURRENT_TIMESTAMP;
END//
CREATE TRIGGER update_reviews_updated_at BEFORE UPDATE ON reviews FOR EACH ROW
BEGIN
    SET NEW.updated_at = CURRENT_TIMESTAMP;
END//
CREATE TRIGGER update_settings_updated_at BEFORE UPDATE ON settings FOR EACH ROW
BEGIN
    SET NEW.updated_at = CURRENT_TIMESTAMP;
END//
DELIMITER ;
