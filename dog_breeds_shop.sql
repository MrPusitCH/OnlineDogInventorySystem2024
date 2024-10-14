-- สร้างฐานข้อมูลใหม่
CREATE DATABASE IF NOT EXISTS dog_breeds_shop;
USE dog_breeds_shop;

-- สร้างตารางผู้ใช้ (users)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(64) NOT NULL,  -- ปรับเป็น 64 เพื่อรองรับการเข้ารหัส SHA-256
    role ENUM('admin', 'manager', 'customer') DEFAULT 'customer',  -- แยกบทบาท
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    email VARCHAR(100) NOT NULL UNIQUE,  -- ตรวจสอบอีเมล์ไม่ซ้ำ
    dob DATE,                   -- วันเดือนปีเกิด (Date of Birth)
    gender ENUM('male', 'female', 'other'), -- เพศ (Gender)
    address TEXT,                -- ที่อยู่ (Address)
    phone VARCHAR(15)            -- หมายเลขโทรศัพท์ (Phone Number)
);

-- สร้างตารางหมวดหมู่สินค้า (categories)
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

-- สร้างตารางสินค้า (products)
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL,  -- สต็อกสินค้า
    image VARCHAR(255),  -- เก็บชื่อไฟล์รูปภาพสินค้า
    category_id INT,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE ON UPDATE CASCADE  -- Cascade Delete/Update
);

-- สร้างตารางคำสั่งซื้อ (orders)
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    product_id INT,
    quantity INT,
    order_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'completed', 'cancelled') DEFAULT 'pending',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- เพิ่มหมวดหมู่
INSERT INTO categories (name) VALUES ('พันธุ์ใหญ่'), ('พันธุ์เล็ก'), ('ขนเยอะ'), ('ขนน้อย');

-- เพิ่มสินค้า
INSERT INTO products (name, description, price, stock, image, category_id) VALUES 
('ปอมเมอเรเนียน', 'พันธุ์เล็ก ขนฟู', 15000.00, 10, 'pomeranian.jpg', 2),
('ชิสุ', 'พันธุ์เล็ก น่ารัก', 12000.00, 8, 'shih_tzu.jpg', 2),
('บูลด็อก', 'พันธุ์กลาง แข็งแรง', 20000.00, 5, 'bulldog.jpg', 1),
('โกลเด้น รีทรีฟเวอร์', 'พันธุ์ใหญ่ ขี้เล่น', 18000.00, 6, 'golden_retriever.jpg', 1),
('ลาบราดอร์', 'พันธุ์ใหญ่ ฉลาด', 22000.00, 7, 'labrador.jpg', 1),
('พุดเดิ้ล', 'พันธุ์เล็ก ขนหยิก', 14000.00, 9, 'poodle.jpg', 3),
('ชิบะ อินุ', 'พันธุ์กลาง ขี้เล่น', 17000.00, 4, 'shiba_inu.jpg', 2),
('บีเกิ้ล', 'พันธุ์กลาง กลิ่นดี', 13000.00, 3, 'beagle.jpg', 1),
('ดัชชุนด์', 'พันธุ์เล็ก ขายาว', 16000.00, 5, 'dachshund.jpg', 2),
('ซานตา มาเรีย', 'พันธุ์ใหญ่ ขี้เล่น', 25000.00, 2, 'saint_bernard.jpg', 1),
('เยอรมันเชพเพิร์ด', 'พันธุ์ใหญ่ ฉลาด', 23000.00, 6, 'german_shepherd.jpg', 1),
('มอลทีส', 'พันธุ์เล็ก ขนยาว', 11000.00, 8, 'maltese.jpg', 3),
('บอร์เดอร์ คอลลี่', 'พันธุ์กลาง ขี้เล่น', 24000.00, 3, 'border_collie.jpg', 1),
('ปั๊ก', 'พันธุ์เล็ก หน้าตาโบก', 13500.00, 5, 'pug.jpg', 2),
('เชา เชา', 'พันธุ์ใหญ่ ขนหนา', 23000.00, 5, 'chow_chow.jpg', 3),
('แซมอยด์', 'พันธุ์ใหญ่ ขนเยอะ', 26000.00, 4, 'samoyed.jpg', 3),
('อลาสกัน มาลามิวท์', 'พันธุ์ใหญ่ ขนหนาและทนทาน', 28000.00, 3, 'alaskan_malamute.jpg', 3),
('ร็อตไวเลอร์', 'พันธุ์ใหญ่ แข็งแรงและเฝ้าบ้าน', 25000.00, 2, 'rottweiler.jpg', 1),
('แจ็ค รัสเซล เทอร์เรียร์', 'พันธุ์เล็ก ขนน้อย คล่องแคล่ว', 14000.00, 6, 'jack_russell.jpg', 4),
('ชิวาวา', 'พันธุ์เล็ก ขนน้อย น่ารัก', 12000.00, 8, 'chihuahua.jpg', 4),
('ดัลเมเชียน', 'พันธุ์กลาง ขนน้อย มีจุด', 18000.00, 5, 'dalmatian.jpg', 4),
('เกรย์ฮาวด์', 'พันธุ์กลาง ขนน้อย วิ่งเร็ว', 23000.00, 3, 'greyhound.jpg', 4),
('วิปเพ็ท', 'พันธุ์กลาง ขนน้อย คล่องแคล่ว', 22000.00, 4, 'whippet.jpg', 4);

-- เพิ่มข้อมูลผู้ใช้ admin
INSERT INTO users (username, password, role, first_name, last_name, email, phone, address)
VALUES ('admin', '123456', 'admin', 'Admin', 'User', 'admin@example.com', '0987654321', '123 Main Street');

-- เพิ่มข้อมูลผู้ใช้ manager ล่วงหน้า
INSERT INTO users (username, password, role, first_name, last_name, email, phone, address)
VALUES ('manage', '1234', 'manager', 'Manager', 'User', 'manager@example.com', '0912345678', '456 Manager Street');
