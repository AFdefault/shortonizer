<?php

use App\Core\Router;

class Autoloader
{
    const BASE_PATH = __DIR__. '/';
    public static function init(): void
    {
        spl_autoload_register(function ($class) {

            $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
            $file = self::BASE_PATH . $class . '.php';

            if (file_exists($file)) {
                require $file;
                return true;
            }
            return false;
        });
    }
}

Autoloader::init();

$method = $_SERVER['REQUEST_METHOD'];
$uri = trim($_SERVER['REQUEST_URI'], '/');

$router = new Router();

//register route
$router->post('/api/short_url', [\App\Controller\ShortUrlController::class, 'create']);
$router->get('/{url_id}', [\App\Controller\ShortUrlController::class, 'get']);
$router->get('/', [\App\Controller\ShortUrlController::class, 'show']);

$router->route($method, $uri);