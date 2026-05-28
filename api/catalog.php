<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

handle_api_errors(function (): void {
    $pdo = db();
    success_response([
        'products' => list_products($pdo),
        'config' => app_config($pdo),
        'updatedAt' => get_setting($pdo, 'catalog_updated_at') ?? now_iso(),
        'admin' => is_admin(),
    ]);
});
