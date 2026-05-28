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
        if (($data['action'] ?? '') === 'update_prices') {
            $prices = $data['prices'] ?? [];
            if (!is_array($prices)) {
                throw new InvalidArgumentException('Передайте массив цен');
            }

            $pdo->beginTransaction();
            try {
                $stmt = $pdo->prepare('UPDATE products SET price = :price WHERE id = :id');
                foreach ($prices as $row) {
                    if (!is_array($row)) {
                        continue;
                    }
                    $id = trim((string) ($row['id'] ?? ''));
                    $priceText = str_replace(',', '.', trim((string) ($row['price'] ?? '')));
                    if ($id === '' || $priceText === '' || !is_numeric($priceText)) {
                        throw new InvalidArgumentException('Проверьте цены перед сохранением');
                    }
                    $price = (float) $priceText;
                    if ($price < 0) {
                        throw new InvalidArgumentException('Цена не может быть отрицательной');
                    }
                    $stmt->execute([
                        ':id' => $id,
                        ':price' => $price,
                    ]);
                }
                $updatedAt = touch_catalog($pdo);
                $pdo->commit();
            } catch (Throwable $e) {
                $pdo->rollBack();
                throw $e;
            }

            success_response([
                'products' => list_products($pdo),
                'updatedAt' => $updatedAt,
            ]);
        }

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
