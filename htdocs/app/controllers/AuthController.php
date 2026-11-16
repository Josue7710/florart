<?php
class AuthController extends Controller {
    private const MAX_ATTEMPTS = 5;
    private const LOCK_SECONDS = 300;

    public function login(): void {
        if (Auth::check()) { $this->redirect(Auth::isAdmin() ? 'admin' : 'my-orders'); }
        $error = '';
        $correo = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->requirePost('login');
            $correo = strtolower(trim($_POST['correo'] ?? ''));
            $password = (string)($_POST['password'] ?? '');

            if ($this->isLocked()) {
                $error = 'Demasiados intentos fallidos. Espera unos minutos e inténtalo de nuevo.';
            } else {
                try {
                    $user = (new User())->authenticate($correo, $password);
                } catch (Throwable $e) {
                    $user = null;
                    $error = 'No se pudo conectar con la base de datos. Revisa los datos de conexión.';
                }
                if ($user) {
                    unset($_SESSION['login_fails'], $_SESSION['login_lock']);
                    Auth::login($user);
                    $this->redirect($user['rol'] === 'admin' ? 'admin' : 'my-orders');
                }
                if ($error === '') {
                    $this->registerFailure();
                    $error = 'Correo o contraseña incorrectos.';
                }
            }
        }
        $this->view('auth/login', ['title' => 'Iniciar sesión', 'error' => $error, 'correo' => $correo]);
    }

    public function register(): void {
        if (Auth::check()) { $this->redirect('my-orders'); }
        $errors = [];
        $old = ['nombre' => '', 'correo' => ''];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->requirePost('register');
            $old['nombre'] = trim($_POST['nombre'] ?? '');
            $old['correo'] = strtolower(trim($_POST['correo'] ?? ''));
            $password = (string)($_POST['password'] ?? '');
            $confirm = (string)($_POST['password_confirm'] ?? '');

            if (mb_strlen($old['nombre']) < 2 || mb_strlen($old['nombre']) > 120) { $errors[] = 'Escribe tu nombre (mínimo 2 caracteres).'; }
            if (!filter_var($old['correo'], FILTER_VALIDATE_EMAIL)) { $errors[] = 'El correo electrónico no es válido.'; }
            if (strlen($password) < 8) { $errors[] = 'La contraseña debe tener al menos 8 caracteres.'; }
            if ($password !== $confirm) { $errors[] = 'Las contraseñas no coinciden.'; }

            if (!$errors) {
                try {
                    $users = new User();
                    if ($users->findByEmail($old['correo'])) {
                        $errors[] = 'Ya existe una cuenta con ese correo.';
                    } else {
                        $id = $users->create($old['nombre'], $old['correo'], $password);
                        Auth::login(['id' => $id, 'nombre' => $old['nombre'], 'correo' => $old['correo'], 'rol' => 'cliente']);
                        $this->flash('success', '¡Cuenta creada! Ya puedes personalizar tu arreglo.');
                        $this->redirect('my-orders');
                    }
                } catch (Throwable $e) {
                    $errors[] = 'No se pudo crear la cuenta. Revisa la conexión con la base de datos.';
                }
            }
        }
        $this->view('auth/register', ['title' => 'Crear cuenta', 'errors' => $errors, 'old' => $old]);
    }

    public function logout(): void {
        $this->requirePost('home');
        Auth::logout();
        session_start();
        $this->flash('success', 'Sesión cerrada.');
        $this->redirect('home');
    }

    private function isLocked(): bool {
        return ($_SESSION['login_lock'] ?? 0) > time();
    }

    private function registerFailure(): void {
        $_SESSION['login_fails'] = ($_SESSION['login_fails'] ?? 0) + 1;
        if ($_SESSION['login_fails'] >= self::MAX_ATTEMPTS) {
            $_SESSION['login_lock'] = time() + self::LOCK_SECONDS;
            $_SESSION['login_fails'] = 0;
        }
    }
}
