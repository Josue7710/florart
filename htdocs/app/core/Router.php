<?php
class Router {
    // página => [Controlador, método, acceso, método HTTP permitido]
    // acceso: public | auth (sesión iniciada) | admin (solo administradores)
    private $routes = [
        'home'                => ['HomeController',    'index',        'public', 'GET'],
        'catalog'             => ['CatalogController', 'index',        'public', 'GET'],
        'customize'           => ['OrderController',   'create',       'public', 'GET'],
        'save-order'          => ['OrderController',   'store',        'public', 'POST'],
        'my-orders'           => ['OrderController',   'mine',         'auth',   'GET'],
        'login'               => ['AuthController',    'login',        'public', null],
        'register'            => ['AuthController',    'register',     'public', null],
        'logout'              => ['AuthController',    'logout',       'public', 'POST'],
        'admin'               => ['AdminController',   'index',        'admin',  'GET'],
        'admin-products'      => ['AdminController',   'products',     'admin',  'GET'],
        'admin-product-new'   => ['AdminController',   'productForm',  'admin',  'GET'],
        'admin-product-edit'  => ['AdminController',   'productForm',  'admin',  'GET'],
        'admin-product-save'  => ['AdminController',   'productSave',  'admin',  'POST'],
        'admin-product-toggle'=> ['AdminController',   'productToggle','admin',  'POST'],
        'admin-product-delete'=> ['AdminController',   'productDelete','admin',  'POST'],
        'admin-orders'        => ['AdminController',   'orders',       'admin',  'GET'],
        'admin-order-status'  => ['AdminController',   'orderStatus',  'admin',  'POST'],
    ];

    public function dispatch(): void {
        $page = $_GET['page'] ?? 'home';
        if (!is_string($page) || !isset($this->routes[$page])) {
            http_response_code(404);
            (new HomeController())->notFound();
            return;
        }
        list($class, $method, $access, $http) = $this->routes[$page];

        if ($http !== null && $_SERVER['REQUEST_METHOD'] !== $http) {
            header('Location: ' . url($http === 'POST' ? 'home' : $page));
            return;
        }
        if ($access !== 'public' && !Auth::check()) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Inicia sesión para continuar.'];
            header('Location: ' . url('login'));
            return;
        }
        if ($access === 'admin' && !Auth::isAdmin()) {
            (new $class())->forbiddenPage();
            return;
        }
        try {
            $controller = new $class();
            $controller->$method();
        } catch (Throwable $e) {
            error_log('FlorArte: ' . $e->getMessage() . ' en ' . $e->getFile() . ':' . $e->getLine());
            if (APP_DEBUG) { throw $e; }
            http_response_code(500);
            (new HomeController())->serverError();
        }
    }
}
