<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

handle_api_errors(function (): void {
    $pdo = db();
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

    if ($method === 'GET') {
        success_response([
            'config' => app_config($pdo),
            'admin' => is_admin(),
        ]);
    }

    if ($method !== 'POST') {
        error_response('Метод не поддерживается', 405);
    }

    verify_csrf();
    require_admin();

    $config = save_app_config($pdo, read_json_body());
    success_response([
        'config' => $config,
        'admin' => true,
    ]);
});
