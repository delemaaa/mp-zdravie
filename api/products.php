<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

handle_api_errors(function (): void {
    $pdo = db();
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

    if ($method === 'GET') {
        success_response([
            'products' => list_products($pdo),
            'updatedAt' => get_setting($pdo, 'catalog_updated_at') ?? now_iso(),
            'admin' => is_admin(),
        ]);
    }

    verify_csrf();
    require_admin();

    if ($method === 'POST') {
        $data = read_json_body();
        if (($data['action'] ?? '') === 'replace') {
            $rows = $data['products'] ?? [];
            if (!is_array($rows)) {
                throw new InvalidArgumentException('Передайте массив товаров');
            }
            replace_products($pdo, $rows);
            success_response([
                'products' => list_products($pdo),
                'updatedAt' => get_setting($pdo, 'catalog_updated_at') ?? now_iso(),
            ]);
        }

        $product = insert_product($pdo, normalize_product_input($data, false));
        $updatedAt = touch_catalog($pdo);
        success_response([
            'product' => $product,
            'products' => list_products($pdo),
            'updatedAt' => $updatedAt,
        ], 201);
    }

    if ($method === 'PUT' || $method === 'PATCH') {
        $data = read_json_body();
        $product = update_product($pdo, normalize_product_input($data, true));
        $updatedAt = touch_catalog($pdo);
        success_response([
            'product' => $product,
            'products' => list_products($pdo),
            'updatedAt' => $updatedAt,
        ]);
    }

    if ($method === 'DELETE') {
        $data = read_json_body();
        $id = trim((string) ($data['id'] ?? $_GET['id'] ?? ''));
        if ($id === '') {
            throw new InvalidArgumentException('Не передан ID товара');
        }
        delete_product($pdo, $id);
        $updatedAt = touch_catalog($pdo);
        success_response([
            'products' => list_products($pdo),
            'updatedAt' => $updatedAt,
        ]);
    }

    error_response('Метод не поддерживается', 405);
});
