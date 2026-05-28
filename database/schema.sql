-- База данных для сайта "Здравие".
-- Этот файл можно выполнить целиком во вкладке SQL в phpMyAdmin.
-- После импорта проверьте данные подключения в api/config.php.

CREATE DATABASE IF NOT EXISTS `zdravie_db`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `zdravie_db`;

CREATE TABLE IF NOT EXISTS `admins` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(80) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_login_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_admins_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `site_settings` (
  `setting_key` VARCHAR(100) NOT NULL,
  `setting_value` TEXT NOT NULL,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `products` (
  `id` VARCHAR(64) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `category` VARCHAR(120) NOT NULL DEFAULT 'Прочее',
  `price` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `unit` VARCHAR(40) NOT NULL DEFAULT 'кг',
  `packaging` VARCHAR(255) NOT NULL DEFAULT '',
  `origin` VARCHAR(120) NOT NULL DEFAULT '',
  `flag` VARCHAR(16) NOT NULL DEFAULT '',
  `emoji` VARCHAR(16) NOT NULL DEFAULT '',
  `in_stock` TINYINT(1) NOT NULL DEFAULT 1,
  `sort_order` INT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_products_category` (`category`),
  KEY `idx_products_stock` (`in_stock`),
  KEY `idx_products_sort` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Стартовый администратор:
-- логин: admin
-- пароль: admin123
-- После первого входа смените пароль в админке.
INSERT INTO `admins` (`username`, `password_hash`)
VALUES ('admin', 'sha256$240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9')
ON DUPLICATE KEY UPDATE `username` = `username`;

INSERT INTO `site_settings` (`setting_key`, `setting_value`) VALUES
  ('config_company', 'Здравие'),
  ('config_phone', '+7 (XXX) XXX-XX-XX'),
  ('config_tg', 'https://t.me/zdravie'),
  ('config_wa', '+79001234567'),
  ('config_mx', 'https://max.ru/zdravie'),
  ('config_stat1', '150+'),
  ('config_stat2', '500+'),
  ('catalog_updated_at', DATE_FORMAT(NOW(), '%Y-%m-%dT%H:%i:%s+00:00'))
ON DUPLICATE KEY UPDATE `setting_value` = `setting_value`;

-- Товары можно добавить через админку сайта.
-- При первом запуске PHP также автоматически перенесёт стартовый прайс из data/products.json,
-- если таблица products пустая.
