-- Create the database
CREATE DATABASE IF NOT EXISTS platedbox;
USE platedbox;

-- Users table to store user information
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Products table to store meal package information
CREATE TABLE products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    type ENUM('Weekly', 'Monthly', 'VIP') NOT NULL,
    features JSON,
    active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Orders table to store order information
CREATE TABLE orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    order_reference VARCHAR(50) UNIQUE NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('PENDING', 'COMPLETED', 'CANCELLED', 'FAILED') NOT NULL,
    payment_id VARCHAR(100),
    delivery_campus VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Order items table to store items within each order
CREATE TABLE order_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price_at_time DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Payments table to store payment information
CREATE TABLE payments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_reference VARCHAR(100) UNIQUE NOT NULL,
    payment_method VARCHAR(50),
    status ENUM('PENDING', 'COMPLETED', 'FAILED', 'REFUNDED') NOT NULL,
    transaction_data JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id)
);

-- Delivery schedules table to manage meal deliveries
CREATE TABLE delivery_schedules (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    delivery_date DATE NOT NULL,
    delivery_time_slot VARCHAR(50) NOT NULL,
    status ENUM('SCHEDULED', 'IN_TRANSIT', 'DELIVERED', 'FAILED') NOT NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id)
);

-- Delivery details table to store customer delivery information
CREATE TABLE delivery_details (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    building_name VARCHAR(255) NOT NULL,
    delivery_address TEXT NOT NULL,
    cellphone VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Create indexes for better query performance
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_username ON users(name);
CREATE INDEX idx_orders_user ON orders(user_id);
CREATE INDEX idx_orders_status ON orders(status);
CREATE INDEX idx_order_items_order ON order_items(order_id);
CREATE INDEX idx_payments_order ON payments(order_id);
CREATE INDEX idx_delivery_schedules_order ON delivery_schedules(order_id);
CREATE INDEX idx_delivery_schedules_date ON delivery_schedules(delivery_date);

-- Insert some initial product data
INSERT INTO products (name, description, price, type, features, active) VALUES
('Weekly Package', '5 dinner meals with sides and snacks', 450.00, 'Weekly', '["5 dinner meals with sides and snacks", "Delivered between 16:00 - 18:00", "Weekly menu rotation", "Cancel anytime"]', TRUE),
('Monthly Package', '20 weekday dinner meals with sides and snacks', 1000.00, 'Monthly', '["20 weekday dinner meals with sides and snacks", "Delivered between 16:00 - 18:00", "Save R800 compared to weekly packages", "Priority delivery"]', TRUE),
('Monthly VIP Package', '20 weekday dinner meals with premium sides and snacks', 2000.00, 'VIP', '["20 weekday dinner meals with premium sides and snacks", "Priority delivery", "Premium menu selection", "Exclusive recipes"]', TRUE); 