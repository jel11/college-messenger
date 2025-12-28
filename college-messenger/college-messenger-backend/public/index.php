<?php

    require_once __DIR__ . '/../vendor/autoload.php';

    // Загружаем переменные окружения из .env
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();

    use App\Controllers\MessageController;
    use App\Router\Router;
    use App\Middleware\CorsMiddleware;
    use App\Controllers\AuthController;
    use App\Controllers\UserController;
    use App\Controllers\GroupController;

    header('Content-Type: application/json');

    $router = new Router();

    $router->addMiddleware([CorsMiddleware::class, 'handle']);

    $router->post('/api/auth/register', [AuthController::class, 'register']);
    $router->post('/api/auth/login', [AuthController::class, 'login']);
    $router->post('/api/auth/logout', [AuthController::class, 'logout']);

    $router->get('/api/users', [UserController::class, 'index']);
    $router->get('/api/users/{id}', [UserController::class, 'show']);
    $router->put('/api/users/{id}', [UserController::class, 'update']);
    $router->delete('/api/users/{id}', [UserController::class, 'delete']);

    $router->get('/api/groups', [GroupController::class, 'index']);
    $router->post('/api/groups', [GroupController::class, 'create']);
    $router->get('/api/groups/{id}', [GroupController::class, 'show']);
    $router->put('/api/groups/{id}', [GroupController::class, 'update']);
    $router->delete('/api/groups/{id}', [GroupController::class, 'delete']);
    $router->post('/api/groups/{id}/join', [GroupController::class, 'join']);
    $router->delete('/api/groups/{id}/leave', [GroupController::class, 'leave']);

    $router->get('/api/groups/{id}/messages', [MessageController::class, 'index']);
    $router->post('/api/groups/{id}/messages', [MessageController::class, 'index']);
    $router->delete('/api/messages/{id}', [MessageController::class, 'delete']);

    $router->dispatch();
?>