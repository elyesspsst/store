-- Delicacy Restaurant Database Schema

-- Categories Table
CREATE TABLE categories (
    category_id INT PRIMARY KEY AUTO_INCREMENT,
    category_name VARCHAR(100) NOT NULL,
    category_name_ar VARCHAR(100),
    display_order INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Products/Menu Items Table
CREATE TABLE products (
    product_id INT PRIMARY KEY AUTO_INCREMENT,
    product_name VARCHAR(150) NOT NULL,
    product_name_ar VARCHAR(150),
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    category_id INT,
    image_url VARCHAR(255),
    is_available BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(category_id)
);

-- Chefs/Staff Table
CREATE TABLE chefs (
    chef_id INT PRIMARY KEY AUTO_INCREMENT,
    full_name VARCHAR(150) NOT NULL,
    position VARCHAR(100) NOT NULL,
    bio TEXT,
    image_url VARCHAR(255),
    facebook_url VARCHAR(255),
    twitter_url VARCHAR(255),
    instagram_url VARCHAR(255),
    linkedin_url VARCHAR(255),
    tiktok_url VARCHAR(255),
    display_order INT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Users/Customers Table
CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(150),
    phone VARCHAR(20),
    address TEXT,
    profile_image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL
);

-- Orders Table
CREATE TABLE orders (
    order_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total_amount DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'confirmed', 'preparing', 'ready', 'delivered', 'cancelled') DEFAULT 'pending',
    delivery_address TEXT,
    phone VARCHAR(20),
    notes TEXT,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- Order Items Table
CREATE TABLE order_items (
    order_item_id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT,
    product_id INT,
    quantity INT NOT NULL,
    unit_price DECIMAL(10, 2) NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(order_id),
    FOREIGN KEY (product_id) REFERENCES products(product_id)
);

-- Cart Table (for temporary storage)
CREATE TABLE cart (
    cart_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    product_id INT,
    quantity INT NOT NULL DEFAULT 1,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (product_id) REFERENCES products(product_id)
);

-- Slides/Banners Table
CREATE TABLE slides (
    slide_id INT PRIMARY KEY AUTO_INCREMENT,
    slide_name VARCHAR(100),
    image_url VARCHAR(255) NOT NULL,
    display_order INT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- SAMPLE DATA INSERTION
-- ============================================

-- Insert Categories
INSERT INTO categories (category_name, category_name_ar, display_order) VALUES
('Mekla Souri', 'مأكولات سريعة', 1),
('Mekla Tounsiya', 'مأكولات تونسية', 2),
('Boissons', 'مشروبات', 3);

-- Insert Products - Mekla Souri (Category 1)
INSERT INTO products (product_name, product_name_ar, price, category_id, image_url) VALUES
('Double Beef Burger', 'برغر لحم مزدوج', 13.00, 1, 'burger.png'),
('Pizza Pepperoni', 'بيتزا بيبروني', 18.00, 1, 'pizza.png'),
('Sandwitch', 'ساندويتش', 9.00, 1, 'sandwich.png'),
('Salade Vert', 'سلطة خضراء', 10.00, 1, 'salade vert.jpeg'),
('Spaghetti', 'سباغيتي', 22.00, 1, 'spaghetti.png'),
('Sushi', 'سوشي', 8.00, 1, 'suchi.png'),
('Chicken Rice', 'أرز بالدجاج', 16.00, 1, 'roz b djej.png'),
('Lasagna', 'لازانيا', 18.00, 1, 'lasagna.png'),
('Gateau Fraise', 'كعكة الفراولة', 25.00, 1, 'gateau.jpeg'),
('Spring Roll', 'سبرينغ رول', 11.00, 1, 'spring-roll.png'),
('Gauffre', 'وافل', 14.00, 1, 'gaufffre.png'),
('Baguette Farcé', 'باغيت محشية', 8.00, 1, 'baguette.png');

-- Insert Products - Mekla Tounsiya (Category 2)
INSERT INTO products (product_name, product_name_ar, price, category_id, image_url) VALUES
('Jelbena', 'جلبانة', 14.00, 2, 'jelbana.png'),
('Kosksi', 'كسكسي', 20.00, 2, 'kosksi.png'),
('Chappati', 'شباتي', 8.00, 2, 'chappati.jpeg'),
('Loubya', 'لوبيا', 19.00, 2, 'loubya.png'),
('Slata Mechwya', 'سلطة مشوية', 8.00, 2, 'salade mecwya.png'),
('S7an Tounsi', 'صحن تونسي', 12.00, 2, 's7an-tounsi.png'),
('Ojja Merguez', 'عجة مرقاز', 11.00, 2, 'ejja merguez.png'),
('Sa7fa Lablebi', 'صحفة لبلابي', 6.00, 2, 'labloubi.png'),
('Masfouf', 'مسفوف', 16.00, 2, 'masfouf.png'),
('Madfouna', 'مدفونة', 16.00, 2, 'madfouna.png'),
('Brik', 'بريك', 4.00, 2, 'brik.png'),
('Fricassé', 'فريكاسي', 4.00, 2, 'frikasé.png');

-- Insert Products - Boissons (Category 3)
INSERT INTO products (product_name, product_name_ar, price, category_id, image_url) VALUES
('Mojito', 'موهيتو', 8.00, 3, 'mojito.png'),
('Soda', 'صودا', 3.00, 3, 'soda.jpeg'),
('Ice Coffee', 'قهوة مثلجة', 8.00, 3, 'ice coffe.jpeg'),
('Jus 3neb', 'عصير عنب', 9.00, 3, 'jus 3neb.jpeg'),
('Jus Banane', 'عصير موز', 9.00, 3, 'babnae.jpeg'),
('Jus Kiwi', 'عصير كيوي', 9.00, 3, 'jus de kiwi.jpeg'),
('Jus Fraise', 'عصير فراولة', 9.00, 3, 'jus frezzz.jpeg'),
('Gazouz', 'غازوز', 2.00, 3, 'gazouz.jpeg'),
('Thé', 'شاي', 2.00, 3, 'tai.jpeg'),
('Kahwa Arbi', 'قهوة عربية', 3.00, 3, 'kahwa arbi.png'),
('Cappuccino', 'كابتشينو', 5.00, 3, 'cappucin.jpeg'),
('Eau Minérale', 'ماء معدني', 1.00, 3, 'eau.jpeg');

-- Insert Chefs
INSERT INTO chefs (full_name, position, image_url, facebook_url, twitter_url, instagram_url, display_order) VALUES
('Clare Smyth', 'Head Chef', 'clare-smith.png', 
 'https://www.facebook.com/p/Clare-Smyth-100057759353792/', 
 '#', 
 'https://www.instagram.com/chefclaresmyth/?hl=fr', 1),

('Chef Burak', 'Pastry Chef', 'burak.png',
 'https://www.facebook.com/cznburak',
 'https://x.com/CznBurak',
 'https://www.instagram.com/cznburak/', 2),

('Nusret', 'Sous Chef', 'nusret.jpeg',
 'https://www.facebook.com/Nusret.fb/',
 'https://x.com/nusr_et?lang=fr',
 'https://www.instagram.com/nusr_et/', 3),

('Nesma Mouhamed', 'Grill Master', 'nesma.png',
 'https://www.facebook.com/p/Nesmas-Kitchen-100063975314427/',
 '#',
 'https://www.instagram.com/archi_chefnesma/', 4),

('Mouhamed Elyes Bouallegui', 'Restaurant Manager', 'mee.jpg',
 'https://www.facebook.com/elies.bouallegui',
 NULL,
 'https://www.instagram.com/eliesbouallegui/', 5),

('Eya Abroug', 'Restaurant Manager Assistant', 'profil.jpg',
 'https://www.facebook.com/eya.abroug.282244',
 NULL,
 'https://www.instagram.com/ayoutta_eyaa/', 6),

('Jamila Bali', 'Fokhar Bekri', 'chef.png',
 'https://www.facebook.com/p/Jamila-Bali-100048757017113/',
 NULL,
 NULL, 7),

('Hamdi l3ou9', 'Cleaner', 'l3ou9.png',
 NULL,
 NULL,
 'https://www.instagram.com/hamdirahalilwq/', 8);

-- Insert Slides
INSERT INTO slides (slide_name, image_url, display_order) VALUES
('Presentation', 'presentation.png', 1),
('Interface 1', 'interface-1.png', 2),
('Healthy Options', 'healthy-3.png', 3),
('Reserve Table', 'Réservez-une-table.png', 4),
('Goodbye', 'bye.png', 5);

-- Add LinkedIn and TikTok columns to chefs table (if needed)
ALTER TABLE chefs 
ADD COLUMN linkedin_url VARCHAR(255) AFTER instagram_url,
ADD COLUMN tiktok_url VARCHAR(255) AFTER linkedin_url;

-- Update specific chefs with additional social media
UPDATE chefs SET linkedin_url = 'https://fr.linkedin.com/in/elias-bouallagui-6104935?trk=people-guest_people_search-card' 
WHERE full_name = 'Mouhamed Elyes Bouallegui';

UPDATE chefs SET tiktok_url = 'https://www.tiktok.com/@hamdirahali5'
WHERE full_name = 'Hamdi l3ou9';