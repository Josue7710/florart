<?php
class OrderController extends Controller {
    public function create(): void {
        $selected = (int)($_GET['product'] ?? 0);
        $form = $this->defaults();
        $form['producto_id'] = $selected ?: '';
        $this->renderForm($form, '');
    }

    public function store(): void {
        $this->requirePost('customize');

        $form = [
            'nombre_cliente' => trim($_POST['nombre_cliente'] ?? ''),
            'correo' => trim($_POST['correo'] ?? ''),
            'tipo_flor' => $_POST['tipo_flor'] ?? 'Natural',
            'colores' => trim($_POST['colores'] ?? ''),
            'tamano' => $_POST['tamano'] ?? 'Mediano',
            'envoltura' => trim($_POST['envoltura'] ?? ''),
            'dedicatoria' => trim($_POST['dedicatoria'] ?? ''),
            'presupuesto' => trim($_POST['presupuesto'] ?? ''),
            'producto_id' => (int)($_POST['producto_id'] ?? 0) ?: '',
        ];

        $errors = [];
        if ($form['nombre_cliente'] === '' || mb_strlen($form['nombre_cliente']) > 120) { $errors[] = 'el nombre'; }
        if (!filter_var($form['correo'], FILTER_VALIDATE_EMAIL)) { $errors[] = 'un correo válido'; }
        if ($form['colores'] === '' || mb_strlen($form['colores']) > 255) { $errors[] = 'los colores'; }
        if (!in_array($form['tipo_flor'], Product::FLOWER_TYPES, true)) { $errors[] = 'el tipo de flor'; }
        if (!in_array($form['tamano'], Order::SIZES, true)) { $errors[] = 'el tamaño'; }
        if (mb_strlen($form['envoltura']) > 150) { $errors[] = 'la envoltura (máx. 150 caracteres)'; }
        if (mb_strlen($form['dedicatoria']) > 1000) { $errors[] = 'la dedicatoria (máx. 1000 caracteres)'; }

        $budget = null;
        if ($form['presupuesto'] !== '') {
            $budget = filter_var($form['presupuesto'], FILTER_VALIDATE_FLOAT);
            if ($budget === false || $budget < 0 || $budget > 99999999) { $errors[] = 'el presupuesto'; $budget = null; }
        }

        if ($errors) {
            $this->renderForm($form, 'Revisa ' . implode(', ', $errors) . '.');
            return;
        }

        try {
            $productId = null;
            if ($form['producto_id'] !== '') {
                $product = (new Product())->find((int)$form['producto_id']);
                $productId = ($product && $product['activo']) ? (int)$product['id'] : null;
            }
            $codigo = (new Order())->create([
                'usuario_id' => Auth::id(),
                'producto_id' => $productId,
                'nombre_cliente' => $form['nombre_cliente'],
                'correo' => $form['correo'],
                'tipo_flor' => $form['tipo_flor'],
                'colores' => $form['colores'],
                'tamano' => $form['tamano'],
                'envoltura' => $form['envoltura'],
                'dedicatoria' => $form['dedicatoria'],
                'presupuesto' => $budget,
            ]);
            $this->view('order_success', ['title' => 'Solicitud recibida', 'codigo' => $codigo]);
        } catch (Throwable $e) {
            $this->renderForm($form, 'No se pudo guardar el pedido. Verifica que la base de datos esté importada y configurada.');
        }
    }

    /** "Mis pedidos": historial del cliente con sesión iniciada. */
    public function mine(): void {
        try {
            $orders = (new Order())->forUser(Auth::id());
        } catch (Throwable $e) {
            $orders = [];
            $this->flash('error', 'No se pudieron cargar tus pedidos.');
        }
        $this->view('orders/mine', ['title' => 'Mis pedidos', 'orders' => $orders]);
    }

    private function defaults(): array {
        $user = Auth::user();
        return [
            'nombre_cliente' => $user['nombre'] ?? '', 'correo' => $user['correo'] ?? '',
            'tipo_flor' => 'Natural', 'colores' => '', 'tamano' => 'Mediano',
            'envoltura' => '', 'dedicatoria' => '', 'presupuesto' => '', 'producto_id' => '',
        ];
    }

    private function renderForm(array $form, string $message): void {
        try {
            $products = (new Product())->all();
        } catch (Throwable $e) {
            $products = [];
            if ($message === '') { $message = 'No se pudo conectar con la base de datos.'; }
        }
        $this->view('customize', [
            'title' => 'Personaliza tu arreglo',
            'products' => $products,
            'form' => $form,
            'message' => $message,
        ]);
    }
}
