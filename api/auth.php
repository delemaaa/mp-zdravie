<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

handle_api_errors(function (): void {
    $pdo = db();
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

    if ($method === 'GET') {
        success_response([
            'admin' => is_admin(),
            'username' => $_SESSION['admin_username'] ?? null,
        ]);
    }

    if ($method !== 'POST') {
        error_response('Метод не поддерживается', 405);
    }

    verify_csrf();
    $data = read_json_body();
    $action = (string) ($data['action'] ?? 'login');

    if ($action === 'login') {
        $username = trim((string) ($data['username'] ?? DEFAULT_ADMIN_USERNAME));
        $password = (string) ($data['password'] ?? '');
        $admin = admin_by_username($pdo, $username);

        if (!$admin || !password_matches($password, (string) $admin['password_hash'])) {
            error_response('Неверный логин или пароль', 401);
        }

        session_regenerate_id(true);
        $_SESSION['admin_authenticated'] = true;
        $_SESSION['admin_username'] = $username;
        $_SESSION['csrf_token'] = bin2hex(random_bytes_safe(32));

        $stmt = $pdo->prepare('UPDATE admins SET last_login_at = CURRENT_TIMESTAMP WHERE id = :id');
        $stmt->execute([':id' => $admin['id']]);

        success_response([
            'admin' => true,
            'username' => $username,
        ]);
    }

    if ($action === 'logout') {
        unset($_SESSION['admin_authenticated'], $_SESSION['admin_username']);
        $_SESSION['csrf_token'] = bin2hex(random_bytes_safe(32));
        success_response(['admin' => false]);
    }

    if ($action === 'change_password') {
        require_admin();
        $password = (string) ($data['password'] ?? '');
        if (strlen($password) < 4) {
            throw new InvalidArgumentException('Минимум 4 символа');
        }

        $username = (string) ($_SESSION['admin_username'] ?? DEFAULT_ADMIN_USERNAME);
        $stmt = $pdo->prepare('UPDATE admins SET password_hash = :password_hash WHERE username = :username');
        $stmt->execute([
            ':password_hash' => password_hash($password, PASSWORD_DEFAULT),
            ':username' => $username,
        ]);

        success_response(['admin' => true]);
    }

    error_response('Неизвестное действие', 422);
});
