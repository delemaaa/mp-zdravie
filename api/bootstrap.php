<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

const DEFAULT_CONFIG = [
    'company' => 'Здравие',
    'phone' => '+7 (XXX) XXX-XX-XX',
    'tg' => 'https://t.me/zdravie',
    'wa' => '+79001234567',
    'mx' => 'https://max.ru/zdravie',
    'stat1' => '150+',
    'stat2' => '500+',
];

start_secure_session();

function start_secure_session(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        ensure_csrf_token();
        return;
    }

    $secure = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';

    if (PHP_VERSION_ID >= 70300) {
        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'domain' => '',
            'secure' => $secure,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
    } else {
        session_set_cookie_params(0, '/; samesite=Lax', '', $secure, true);
    }

    session_name('zdravie_session');
    session_start();
    ensure_csrf_token();
}

function ensure_csrf_token(): void
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes_safe(32));
    }
}

function csrf_token(): string
{
    ensure_csrf_token();
    return (string) $_SESSION['csrf_token'];
}

function random_bytes_safe(int $length): string
{
    try {
        return random_bytes($length);
    } catch (Throwable $e) {
        return hash('sha256', uniqid('', true) . mt_rand(), true);
    }
}

function db(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $database = db_identifier(DB_NAME);
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    try {
        $dsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . $database . ';charset=' . DB_CHARSET;
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (PDOException $e) {
        $dsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';charset=' . DB_CHARSET;
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$database` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $pdo->exec("USE `$database`");
    }

    migrate_database($pdo);

    return $pdo;
}

function db_identifier(string $name): string
{
    if (!preg_match('/^[A-Za-z0-9_]+$/', $name)) {
        throw new RuntimeException('DB_NAME может содержать только латинские буквы, цифры и подчёркивания');
    }

    return $name;
}

function migrate_database(PDO $pdo): void
{
    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS admins (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(80) NOT NULL UNIQUE,
            password_hash VARCHAR(255) NOT NULL,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            last_login_at TIMESTAMP NULL DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
    );

    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS site_settings (
            setting_key VARCHAR(100) NOT NULL PRIMARY KEY,
            setting_value TEXT NOT NULL,
            updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
    );

    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS products (
            id VARCHAR(64) NOT NULL PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            category VARCHAR(120) NOT NULL DEFAULT \'Прочее\',
            price DECIMAL(12,2) NOT NULL DEFAULT 0.00,
            unit VARCHAR(40) NOT NULL DEFAULT \'кг\',
            packaging VARCHAR(255) NOT NULL DEFAULT \'\',
            origin VARCHAR(120) NOT NULL DEFAULT \'\',
            flag VARCHAR(16) NOT NULL DEFAULT \'\',
            emoji VARCHAR(16) NOT NULL DEFAULT \'\',
            in_stock TINYINT(1) NOT NULL DEFAULT 1,
            sort_order INT NOT NULL DEFAULT 0,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_products_category (category),
            INDEX idx_products_stock (in_stock),
            INDEX idx_products_sort (sort_order)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
    );

    ensure_default_admin($pdo);

    foreach (DEFAULT_CONFIG as $key => $value) {
        if (get_setting($pdo, 'config_' . $key) === null) {
            set_setting($pdo, 'config_' . $key, $value);
        }
    }

    if (get_setting($pdo, 'catalog_updated_at') === null) {
        set_setting($pdo, 'catalog_updated_at', now_iso());
    }

    $count = (int) $pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();
    if ($count === 0) {
        seed_products($pdo);
    }
}

function ensure_default_admin(PDO $pdo): void
{
    $stmt = $pdo->prepare('SELECT id FROM admins WHERE username = :username LIMIT 1');
    $stmt->execute([':username' => DEFAULT_ADMIN_USERNAME]);
    if ($stmt->fetchColumn() !== false) {
        return;
    }

    $hash = 'sha256$' . DEFAULT_ADMIN_PASSWORD_SHA256;
    $stmt = $pdo->prepare('INSERT INTO admins (username, password_hash) VALUES (:username, :password_hash)');
    $stmt->execute([
        ':username' => DEFAULT_ADMIN_USERNAME,
        ':password_hash' => $hash,
    ]);
}

function seed_products(PDO $pdo): void
{
    $jsonFile = __DIR__ . '/../data/products.json';
    if (!is_file($jsonFile)) {
        return;
    }

    $rows = json_decode((string) file_get_contents($jsonFile), true);
    if (!is_array($rows)) {
        return;
    }

    $pdo->beginTransaction();
    try {
        $sort = 1;
        foreach ($rows as $row) {
            if (!is_array($row)) {
                continue;
            }
            insert_product($pdo, normalize_product_input($row, false), $sort++);
        }
        $pdo->commit();
        touch_catalog($pdo);
    } catch (Throwable $e) {
        $pdo->rollBack();
        throw $e;
    }
}

function admin_by_username(PDO $pdo, string $username): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM admins WHERE username = :username LIMIT 1');
    $stmt->execute([':username' => $username]);
    $admin = $stmt->fetch();

    return is_array($admin) ? $admin : null;
}

function password_matches(string $password, string $storedHash): bool
{
    if (strpos($storedHash, 'sha256$') === 0) {
        return hash_equals(substr($storedHash, 7), hash('sha256', $password));
    }

    return password_verify($password, $storedHash);
}

function get_setting(PDO $pdo, string $key): ?string
{
    $stmt = $pdo->prepare('SELECT setting_value FROM site_settings WHERE setting_key = :key');
    $stmt->execute([':key' => $key]);
    $value = $stmt->fetchColumn();

    return $value === false ? null : (string) $value;
}

function set_setting(PDO $pdo, string $key, string $value): void
{
    $stmt = $pdo->prepare(
        'INSERT INTO site_settings (setting_key, setting_value)
         VALUES (:setting_key, :setting_value)
         ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)'
    );
    $stmt->execute([
        ':setting_key' => $key,
        ':setting_value' => $value,
    ]);
}

function now_iso(): string
{
    return date('c');
}

function touch_catalog(PDO $pdo): string
{
    $stamp = now_iso();
    set_setting($pdo, 'catalog_updated_at', $stamp);
    return $stamp;
}

function app_config(PDO $pdo): array
{
    $config = DEFAULT_CONFIG;
    foreach (array_keys(DEFAULT_CONFIG) as $key) {
        $value = get_setting($pdo, 'config_' . $key);
        if ($value !== null) {
            $config[$key] = $value;
        }
    }

    return $config;
}

function save_app_config(PDO $pdo, array $input): array
{
    $config = app_config($pdo);
    foreach (array_keys(DEFAULT_CONFIG) as $key) {
        if (array_key_exists($key, $input)) {
            $config[$key] = trim((string) $input[$key]);
        }
        set_setting($pdo, 'config_' . $key, (string) $config[$key]);
    }

    return $config;
}

function list_products(PDO $pdo): array
{
    $stmt = $pdo->query(
        'SELECT id, name, category, price, unit, packaging, origin, flag, emoji, in_stock AS inStock
         FROM products
         ORDER BY sort_order ASC, CAST(id AS UNSIGNED) ASC, name ASC'
    );

    $products = [];
    foreach ($stmt->fetchAll() as $row) {
        $row['id'] = (string) $row['id'];
        $row['price'] = (float) $row['price'];
        if ((float) (int) $row['price'] === $row['price']) {
            $row['price'] = (int) $row['price'];
        }
        $row['inStock'] = (bool) $row['inStock'];
        $products[] = $row;
    }

    return $products;
}

function normalize_product_input(array $input, bool $requireId): array
{
    $id = trim((string) ($input['id'] ?? ''));
    if ($requireId && $id === '') {
        throw new InvalidArgumentException('Не передан ID товара');
    }
    if ($id === '') {
        $id = generate_product_id();
    }

    $name = trim((string) ($input['name'] ?? $input['название'] ?? ''));
    if ($name === '') {
        throw new InvalidArgumentException('Заполните название товара');
    }

    $rawPrice = str_replace(',', '.', (string) ($input['price'] ?? $input['цена'] ?? ''));
    $priceText = preg_replace('/[^0-9.\-]/u', '', $rawPrice);
    if ($priceText === '' || !is_numeric($priceText)) {
        throw new InvalidArgumentException('Заполните корректную цену');
    }
    $price = (float) $priceText;
    if ($price < 0) {
        throw new InvalidArgumentException('Цена не может быть отрицательной');
    }

    return [
        'id' => $id,
        'name' => $name,
        'category' => trim((string) ($input['category'] ?? $input['категория'] ?? 'Прочее')) ?: 'Прочее',
        'price' => $price,
        'unit' => trim((string) ($input['unit'] ?? $input['единица'] ?? 'кг')) ?: 'кг',
        'packaging' => trim((string) ($input['packaging'] ?? $input['pack'] ?? $input['фасовка'] ?? '')),
        'origin' => trim((string) ($input['origin'] ?? $input['country'] ?? $input['страна'] ?? '')),
        'flag' => trim((string) ($input['flag'] ?? $input['флаг'] ?? '')),
        'emoji' => trim((string) ($input['emoji'] ?? $input['эмодзи'] ?? '')),
        'inStock' => bool_value($input['inStock'] ?? $input['stock'] ?? true),
    ];
}

function bool_value($value): bool
{
    if (is_bool($value)) {
        return $value;
    }

    $text = trim(strtolower((string) $value));
    return in_array($text, ['1', 'true', 'yes', 'on', 'да'], true);
}

function generate_product_id(): string
{
    return date('YmdHis') . '-' . bin2hex(random_bytes_safe(4));
}

function insert_product(PDO $pdo, array $product, ?int $sortOrder = null): array
{
    if ($sortOrder === null) {
        $sortOrder = ((int) $pdo->query('SELECT COALESCE(MAX(sort_order), 0) FROM products')->fetchColumn()) + 1;
    }

    $stmt = $pdo->prepare(
        'INSERT INTO products
            (id, name, category, price, unit, packaging, origin, flag, emoji, in_stock, sort_order)
         VALUES
            (:id, :name, :category, :price, :unit, :packaging, :origin, :flag, :emoji, :in_stock, :sort_order)'
    );
    $stmt->execute([
        ':id' => $product['id'],
        ':name' => $product['name'],
        ':category' => $product['category'],
        ':price' => $product['price'],
        ':unit' => $product['unit'],
        ':packaging' => $product['packaging'],
        ':origin' => $product['origin'],
        ':flag' => $product['flag'],
        ':emoji' => $product['emoji'],
        ':in_stock' => $product['inStock'] ? 1 : 0,
        ':sort_order' => $sortOrder,
    ]);

    return $product;
}

function update_product(PDO $pdo, array $product): array
{
    $stmt = $pdo->prepare(
        'UPDATE products
         SET name = :name,
             category = :category,
             price = :price,
             unit = :unit,
             packaging = :packaging,
             origin = :origin,
             flag = :flag,
             emoji = :emoji,
             in_stock = :in_stock
         WHERE id = :id'
    );
    $stmt->execute([
        ':id' => $product['id'],
        ':name' => $product['name'],
        ':category' => $product['category'],
        ':price' => $product['price'],
        ':unit' => $product['unit'],
        ':packaging' => $product['packaging'],
        ':origin' => $product['origin'],
        ':flag' => $product['flag'],
        ':emoji' => $product['emoji'],
        ':in_stock' => $product['inStock'] ? 1 : 0,
    ]);

    if ($stmt->rowCount() === 0 && !product_exists($pdo, $product['id'])) {
        throw new InvalidArgumentException('Товар не найден');
    }

    return $product;
}

function product_exists(PDO $pdo, string $id): bool
{
    $stmt = $pdo->prepare('SELECT 1 FROM products WHERE id = :id LIMIT 1');
    $stmt->execute([':id' => $id]);

    return $stmt->fetchColumn() !== false;
}

function delete_product(PDO $pdo, string $id): void
{
    $stmt = $pdo->prepare('DELETE FROM products WHERE id = :id');
    $stmt->execute([':id' => $id]);

    if ($stmt->rowCount() === 0) {
        throw new InvalidArgumentException('Товар не найден');
    }
}

function replace_products(PDO $pdo, array $rows): void
{
    $pdo->beginTransaction();
    try {
        $pdo->exec('DELETE FROM products');
        $usedIds = [];
        $sort = 1;

        foreach ($rows as $row) {
            if (!is_array($row)) {
                continue;
            }

            $product = normalize_product_input($row, false);
            while (isset($usedIds[$product['id']])) {
                $product['id'] = generate_product_id();
            }
            $usedIds[$product['id']] = true;
            insert_product($pdo, $product, $sort++);
        }

        touch_catalog($pdo);
        $pdo->commit();
    } catch (Throwable $e) {
        $pdo->rollBack();
        throw $e;
    }
}

function read_json_body(): array
{
    $raw = file_get_contents('php://input');
    if ($raw === false || trim($raw) === '') {
        return [];
    }

    $data = json_decode($raw, true);
    if (!is_array($data)) {
        throw new InvalidArgumentException('Некорректный JSON');
    }

    return $data;
}

function json_response(array $payload, int $status = 200): void
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function success_response(array $payload = [], int $status = 200): void
{
    json_response(['ok' => true, 'csrf' => csrf_token()] + $payload, $status);
}

function error_response(string $message, int $status = 400): void
{
    json_response(['ok' => false, 'error' => $message, 'csrf' => csrf_token()], $status);
}

function is_admin(): bool
{
    return !empty($_SESSION['admin_authenticated']);
}

function require_admin(): void
{
    if (!is_admin()) {
        error_response('Нужно войти в админку', 401);
    }
}

function verify_csrf(): void
{
    $token = (string) ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? '');
    if ($token === '' || !hash_equals(csrf_token(), $token)) {
        error_response('Сессия устарела. Обновите страницу и войдите снова.', 419);
    }
}

function handle_api_errors(callable $handler): void
{
    try {
        $handler();
    } catch (InvalidArgumentException $e) {
        error_response($e->getMessage(), 422);
    } catch (PDOException $e) {
        error_response('Ошибка базы данных: ' . $e->getMessage(), 500);
    } catch (Throwable $e) {
        error_response('Ошибка сервера: ' . $e->getMessage(), 500);
    }
}
