CREATE DATABASE IF NOT EXISTS `bid_for_used_product`;
USE `bid_for_used_product`;

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS product_gallery;
DROP TABLE IF EXISTS contact_messages;

DROP TABLE IF EXISTS site_settings;
DROP TABLE IF EXISTS messages;
DROP TABLE IF EXISTS subscriptions;
DROP TABLE IF EXISTS product_reminders;
DROP TABLE IF EXISTS notifications;
DROP TABLE IF EXISTS bids;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS companies;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    role ENUM('admin','company','client') NOT NULL DEFAULT 'client',
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    contact VARCHAR(20),
    address TEXT,
    avatar VARCHAR(255) DEFAULT NULL,
    status ENUM('active','inactive','banned') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE companies (
    company_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    company_name VARCHAR(150),
    owner_name VARCHAR(100),
    gst_number VARCHAR(50),
    identity_proof TEXT DEFAULT NULL,
    verified_status ENUM('pending','verified','rejected') DEFAULT 'pending',
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    product_name VARCHAR(200) NOT NULL,
    category VARCHAR(50),
    model VARCHAR(100),
    year INT,
    chassis_no VARCHAR(100),
    owner_details TEXT,
    running_duration VARCHAR(50),
    base_price DECIMAL(15,2),
    bid_start DATETIME,
    bid_end DATETIME,
    product_image VARCHAR(255),
    status ENUM('open','closed','sold') DEFAULT 'open',
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(company_id) ON DELETE CASCADE
);

CREATE TABLE bids (
    bid_id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    client_id INT NOT NULL,
    bid_amount DECIMAL(15,2) NOT NULL,
    bid_status ENUM('pending','approved','rejected') DEFAULT 'pending',
    bid_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE,
    FOREIGN KEY (client_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE notifications (
    notification_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT,
    type VARCHAR(50) DEFAULT 'info',
    target_url VARCHAR(255),
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE product_reminders (
    reminder_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE
);

CREATE TABLE subscriptions (
    subscription_id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
 
CREATE TABLE messages (
    message_id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    product_id INT,
    message TEXT,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE product_gallery (
    gallery_id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(product_id) ON DELETE CASCADE
);

CREATE TABLE site_settings (
    setting_id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE contact_messages (
    message_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100),
    subject VARCHAR(200),
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO site_settings (setting_key, setting_value) VALUES ('maintenance_mode', '0');
INSERT INTO site_settings (setting_key, setting_value) VALUES ('site_name', 'Bid For Used Product');

INSERT INTO users (role, name, email, password, status) VALUES ('admin', 'System Admin', 'admin@example.com', '$2y$10$mdHjI0QUCfxLn9pFG2NVZOBQgzaYbG3L7UQmoX/liKqQCj/NkXwe6', 'active');
INSERT INTO users (role, name, email, password, status) VALUES ('company', 'Demo Company', 'company@example.com', '$2y$10$5B623E3hgfjzbof059l3BO4gewaJxP6b8M5cJuF.HkfWGU/NiKkp2', 'active');
INSERT INTO users (role, name, email, password, status) VALUES ('client', 'Demo Client', 'client@example.com', '$2y$10$kYMaOpVYB2MWVfN.Bs1uzuzcd6GkIj4d0XCg.YoWKGn0UoORzURV.', 'active');

-- Add Company details for the demo company
INSERT INTO companies (user_id, company_name, owner_name, gst_number, verified_status) VALUES (2, 'Demo Company', 'Demo Owner', '33AAACH1234F1Z1', 'verified');

SET FOREIGN_KEY_CHECKS = 1;


