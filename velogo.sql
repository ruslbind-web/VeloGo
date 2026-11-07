-- База данных для сервиса проката велосипедов VeloGo
-- Создание базы данных
CREATE DATABASE IF NOT EXISTS `velogo` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `velogo`;

-- 1. Таблица станций проката (stations)
DROP TABLE IF EXISTS `user_favorites`;
DROP TABLE IF EXISTS `rentals`;
DROP TABLE IF EXISTS `bikes`;
DROP TABLE IF EXISTS `bike_types`;
DROP TABLE IF EXISTS `stations`;
DROP TABLE IF EXISTS `contact_messages`;
DROP TABLE IF EXISTS `users`;

CREATE TABLE `stations` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `code` VARCHAR(50) NOT NULL UNIQUE,
    `name` VARCHAR(128) NOT NULL,
    `address` VARCHAR(255) NOT NULL,
    `capacity` INT DEFAULT 20,
    `is_active` TINYINT(1) DEFAULT 1,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Таблица категорий/типов велосипедов (bike_types)
CREATE TABLE `bike_types` (
    `code` VARCHAR(32) PRIMARY KEY,
    `name` VARCHAR(64) NOT NULL,
    `label` VARCHAR(64) NOT NULL,
    `icon_svg` VARCHAR(64) DEFAULT NULL,
    `description` TEXT DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Таблица велосипедов (bikes)
CREATE TABLE `bikes` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `slug` VARCHAR(64) NOT NULL UNIQUE,
    `name` VARCHAR(128) NOT NULL,
    `type_code` VARCHAR(32) NOT NULL,
    `type_label` VARCHAR(64) NOT NULL,
    `price_hour` INT NOT NULL,
    `price_day` INT NOT NULL,
    `size` VARCHAR(10) NOT NULL DEFAULT 'M',
    `image` VARCHAR(255) NOT NULL,
    `description` TEXT NOT NULL,
    `rating` DECIMAL(3, 2) DEFAULT 5.00,
    `reviews_count` INT DEFAULT 0,
    `badge` VARCHAR(64) DEFAULT NULL,
    `range_km` VARCHAR(64) DEFAULT NULL,
    `weight` VARCHAR(64) DEFAULT NULL,
    `brakes` VARCHAR(128) DEFAULT NULL,
    `frame` VARCHAR(128) DEFAULT NULL,
    `status` ENUM('available', 'rented', 'maintenance') DEFAULT 'available',
    `current_station_id` INT DEFAULT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_bikes_type` FOREIGN KEY (`type_code`) REFERENCES `bike_types` (`code`) ON UPDATE CASCADE,
    CONSTRAINT `fk_bikes_station` FOREIGN KEY (`current_station_id`) REFERENCES `stations` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Таблица пользователей (users)
CREATE TABLE `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(64) NOT NULL UNIQUE,
    `auth_key` VARCHAR(32) NOT NULL,
    `password_hash` VARCHAR(255) NOT NULL,
    `password_reset_token` VARCHAR(255) DEFAULT NULL,
    `email` VARCHAR(128) NOT NULL UNIQUE,
    `full_name` VARCHAR(128) NOT NULL,
    `phone` VARCHAR(32) NOT NULL,
    `birth_date` DATE DEFAULT NULL,
    `default_address` VARCHAR(255) DEFAULT NULL,
    `bonus_points` INT DEFAULT 0,
    `role` VARCHAR(20) DEFAULT 'user',
    `is_verified` TINYINT(1) DEFAULT 1,
    `status` SMALLINT DEFAULT 10,
    `created_at` INT NOT NULL,
    `updated_at` INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Таблица аренд / бронирований (rentals)
CREATE TABLE `rentals` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `rental_number` VARCHAR(32) NOT NULL UNIQUE,
    `user_id` INT NOT NULL,
    `bike_id` INT NOT NULL,
    `start_station_id` INT NOT NULL,
    `end_station_id` INT DEFAULT NULL,
    `tariff_type` ENUM('hourly', 'daily', 'weekend', 'week') NOT NULL DEFAULT 'hourly',
    `start_time` DATETIME NOT NULL,
    `end_time` DATETIME DEFAULT NULL,
    `duration_hours` INT DEFAULT 1,
    `pin_code` VARCHAR(10) NOT NULL DEFAULT '8492',
    `helmet_included` TINYINT(1) DEFAULT 0,
    `insurance_included` TINYINT(1) DEFAULT 0,
    `holder_included` TINYINT(1) DEFAULT 0,
    `base_price` DECIMAL(10,2) NOT NULL,
    `addons_price` DECIMAL(10,2) DEFAULT 0.00,
    `discount_amount` DECIMAL(10,2) DEFAULT 0.00,
    `total_price` DECIMAL(10,2) NOT NULL,
    `status` ENUM('active', 'completed', 'cancelled') NOT NULL DEFAULT 'active',
    `route_name` VARCHAR(128) DEFAULT NULL,
    `distance_km` DECIMAL(5,2) DEFAULT 0.00,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_rentals_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_rentals_bike` FOREIGN KEY (`bike_id`) REFERENCES `bikes` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `fk_rentals_start_station` FOREIGN KEY (`start_station_id`) REFERENCES `stations` (`id`),
    CONSTRAINT `fk_rentals_end_station` FOREIGN KEY (`end_station_id`) REFERENCES `stations` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20491 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Таблица избранных велосипедов (user_favorites)
CREATE TABLE `user_favorites` (
    `user_id` INT NOT NULL,
    `bike_id` INT NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`user_id`, `bike_id`),
    CONSTRAINT `fk_fav_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_fav_bike` FOREIGN KEY (`bike_id`) REFERENCES `bikes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Таблица сообщений с формы контактов (contact_messages)
CREATE TABLE `contact_messages` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(128) NOT NULL,
    `email` VARCHAR(128) NOT NULL,
    `subject` VARCHAR(255) NOT NULL,
    `body` TEXT NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =========================================================================
-- НАЧАЛЬНЫЕ ДАННЫЕ (SEED DATA)
-- =========================================================================

-- Станции
INSERT INTO `stations` (`id`, `code`, `name`, `address`, `capacity`) VALUES
(1, 'center', 'Станция №1 — Парк Горького (Центр)', 'Санкт-Петербург, ул. Центральная, 1', 25),
(2, 'embankment', 'Станция №2 — Приморская набережная', 'Санкт-Петербург, Набережная р. Мойки, 42', 20),
(3, 'parks', 'Станция №3 — Парк 300-летия', 'Санкт-Петербург, Приморский пр., 74', 30),
(4, 'primorsky', 'Станция №4 — Приморский парк Победы', 'Санкт-Петербург, Крестовский остров, аллея Славы, 2', 20),
(5, 'metro', 'Станция №5 — Ст. м. Петроградская', 'Санкт-Петербург, Каменноостровский пр., 37', 15);

-- Категории
INSERT INTO `bike_types` (`code`, `name`, `label`, `icon_svg`, `description`) VALUES
('electro', 'Электробайки', 'ЭЛЕКТРОБАЙК E-BIKE', 'bolt.svg', 'Мощный электромотор, ассистент педалирования до 25 км/ч, встроенная USB-зарядка.'),
('city', 'Городские комфорт', 'ГОРОДСКОЙ КОМФОРТ', 'bike.svg', 'Анатомическое седло, планетарные втулки, корзина для личных вещей.'),
('mtb', 'Горные (MTB / Трейл)', 'ГОРНЫЙ MTB', 'flag.svg', 'Амортизационные вилки, зубастый протектор, гидравлические тормоза для грунтовых троп.'),
('family', 'Семейные с креслом', 'СЕМЕЙНЫЙ КРУИЗЕР', 'shield.svg', 'Усиленная геометрия и сертифицированное кресло Thule с ремнями безопасности для детей.'),
('gravel', 'Гравийные / Шоссе', 'ГРАВИЙНЫЙ / ШОССЕ', 'sparkles.svg', 'Облегченная рама, жесткая вилка, шоссейный руль-баран для максимальной скорости.');

-- Велосипеды
INSERT INTO `bikes` (`id`, `slug`, `name`, `type_code`, `type_label`, `price_hour`, `price_day`, `size`, `image`, `description`, `rating`, `reviews_count`, `badge`, `range_km`, `weight`, `brakes`, `frame`, `status`, `current_station_id`) VALUES
(1, 'voltride', 'VoltRide Urban Pro', 'electro', 'ЭЛЕКТРОБАЙК E-BIKE', 550, 1900, 'M', 'images/voltride.jpg', 'Мотор 250W с ассистентом до 25 км/ч, встроенная USB-зарядка для смартфона.', 4.95, 120, 'Хит продаж', 'до 65 км', '18.2 кг', 'Дисковые гидравлические', 'Алюминий 6061', 'available', 1),
(2, 'city', 'City Cruiser Comfort', 'city', 'ГОРОДСКОЙ КОМФОРТ', 300, 1100, 'M', 'images/city.jpg', 'Анатомическое седло, 7 скоростей Shimano, передняя корзина для сумок.', 4.90, 84, 'Комфорт', 'Без батареи', '14.5 кг', 'V-Brake Promax', 'Сталь Hi-Ten', 'available', 1),
(3, 'trailmaster', 'TrailMaster Pro 29"', 'mtb', 'ГОРНЫЙ MTB', 450, 1600, 'L', 'images/trailmaster.jpg', 'Вилка RockShox 100мм, дисковая гидравлика Shimano, покрышки Maxxis.', 4.80, 62, 'Внедорожный', 'Без батареи', '13.8 кг', 'Гидравлика Shimano MT200', 'Алюминий Butted', 'available', 2),
(4, 'foldmaster', 'FoldMaster City 20', 'city', 'КОМПАКТ & МЕТРО', 320, 1150, 'S', 'images/foldmaster.jpg', 'Складывается за 15 секунд, вес 11 кг. Легко перевозить в багажнике и метро.', 4.70, 45, 'Компактный', 'Без батареи', '11.2 кг', 'V-Brake Tektro', 'Алюминий складная', 'available', 5),
(5, 'family', 'FamilyTour + Thule Seat', 'family', 'СЕМЕЙНЫЙ КРУИЗЕР', 490, 1750, 'M', 'images/family.jpg', 'Оснащен сертифицированным креслом безопасности для детей до 22 кг с ремнями 5-точек.', 4.98, 77, 'Семейный', 'Без батареи', '16.8 кг', 'Дисковые механические', 'Алюминий усиленная', 'available', 3),
(6, 'gravel', 'Gravel Speedster 500', 'gravel', 'ГРАВИЙНЫЙ / ШОССЕ', 600, 2200, 'L', 'images/gravel.jpg', 'Облегченная рама 9.4 кг, трансмиссия Shimano GRX 2x11, руль-баран для скорости.', 4.90, 53, 'Скорость', 'Без батареи', '9.4 кг', 'Гидравлика Shimano GRX', 'Карбон / Алюминий', 'available', 4),
(7, 'ecargo', 'E-Cargo Transporter', 'electro', 'ГРУЗОВОЙ ЭЛЕКТРОБАЙК', 750, 2700, 'M', 'images/ecargo.jpg', 'Передняя деревянная платформа для пикников, вещей или двух детей. Мощный мотор 500W.', 4.90, 29, 'Грузовой', 'до 50 км', '28.5 кг', 'Дисковая гидравлика', 'Сталь усиленная', 'available', 1),
(8, 'fatbike', 'ElectroScout Fatbike', 'electro', 'ЭЛЕКТРО-ВЕЗДЕХОД', 650, 2400, 'L', 'images/fatbike.jpg', 'Сверхширокие покрышки 4.0" проедут по песку Финского залива и лесным тропинкам без пробуксовки.', 4.88, 41, 'Вездеход', 'до 55 км', '24.0 кг', 'Гидравлика 180мм', 'Алюминий Fat-geometry', 'available', 3);

-- Пользователи (хэш пароля 'admin123' и 'demo123')
-- password_hash для 'admin123' и 'demo123'
INSERT INTO `users` (`id`, `username`, `auth_key`, `password_hash`, `email`, `full_name`, `phone`, `birth_date`, `default_address`, `bonus_points`, `role`, `is_verified`, `status`, `created_at`, `updated_at`) VALUES
(1, 'anna', 'auth_key_anna_kovaleva_12345678', 'y$uL167bP3PbrPJDh.0GoWrO3NdXwZ8ag8MF7YZ2cSNlA9dfRbNw83e', 'anna.k@mail.ru', 'Анна Ковалёва', '+7 (999) 123-45-67', '1998-06-14', 'Санкт-Петербург, Приморский парк Победы, станция №4', 420, 'user', 1, 10, 1712832000, 1712832000),
(2, 'admin', 'auth_key_admin_secret_987654321', 'y$Xm/w/bneSFGBp.f7OrLie.K1OA9SefTBPGvIrF3o.o0gEQehPevrW', 'admin@velogo.ru', 'Главный Администратор', '+7 (800) 555-35-35', '1990-01-01', 'Санкт-Петербург, Парк Горького', 1000, 'admin', 1, 10, 1704067200, 1704067200);

-- Аренды для Анны Ковалёвой (текущая активная и история)
INSERT INTO `rentals` (`id`, `rental_number`, `user_id`, `bike_id`, `start_station_id`, `end_station_id`, `tariff_type`, `start_time`, `end_time`, `duration_hours`, `pin_code`, `helmet_included`, `insurance_included`, `holder_included`, `base_price`, `addons_price`, `discount_amount`, `total_price`, `status`, `route_name`, `distance_km`, `created_at`) VALUES
(20491, '20491', 1, 1, 1, NULL, 'hourly', NOW() - INTERVAL 1 HOUR - INTERVAL 42 MINUTE, NULL, 2, '8492', 1, 1, 0, 1100.00, 0.00, 0.00, 1100.00, 'active', NULL, 14.20, NOW() - INTERVAL 2 HOUR),
(19842, '19842', 1, 2, 1, 2, 'hourly', '2026-05-12 14:00:00', '2026-05-12 16:30:00', 3, '4819', 1, 0, 0, 900.00, 0.00, 50.00, 850.00, 'completed', 'Станция Центр → Станция Набережная', 12.50, '2026-05-12 14:00:00'),
(18210, '18210', 1, 3, 3, 3, 'daily', '2026-04-28 10:00:00', '2026-04-28 18:00:00', 8, '3190', 1, 1, 1, 1600.00, 250.00, 0.00, 1850.00, 'completed', 'Маршрут «Лесопарковое кольцо»', 36.80, '2026-04-28 10:00:00'),
(17409, '17409', 1, 1, 1, 1, 'hourly', '2026-04-15 12:00:00', '2026-04-15 12:05:00', 1, '7721', 0, 0, 0, 550.00, 0.00, 0.00, 550.00, 'cancelled', 'Отмена до начала поездки', 0.00, '2026-04-15 12:00:00');

-- Избранные велосипеды Анны
INSERT INTO `user_favorites` (`user_id`, `bike_id`) VALUES
(1, 1),
(1, 2),
(1, 3);
