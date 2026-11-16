<?php
class Controller {
    protected function view(string $name, array $data = []): void {
        extract($data);
        require __DIR__ . '/../views/layout/header.php';
        require __DIR__ . '/../views/' . $name . '.php';
        require __DIR__ . '/../views/layout/footer.php';
    }

    protected function redirect(string $page, array $params = []): void {
        header('Location: ' . url($page, $params));
        exit;
    }

    protected function flash(string $type, string $message): void {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }

    /** Solo permite POST con token CSRF válido. */
    protected function requirePost(string $backPage): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect($backPage);
        }
        if (!csrf_valid()) {
            http_response_code(419);
            $this->view('error', ['title' => 'Sesión expirada', 'heading' => 'Sesión expirada',
                'text' => 'No se pudo validar el formulario. Vuelve atrás, recarga la página e inténtalo otra vez.']);
            exit;
        }
    }

    public function forbiddenPage(): void {
        http_response_code(403);
        $this->view('error', ['title' => 'Acceso denegado', 'heading' => 'Acceso denegado',
            'text' => 'No tienes permisos para ver esta página.']);
    }
}
