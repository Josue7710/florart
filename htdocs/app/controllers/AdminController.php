<?php
class AdminController extends Controller {
    public function index(): void {
        $orders = new Order();
        try {
            $stats = $orders->stats();
            $stats['products'] = (new Product())->countActive();
            $recent = $orders->recent(5);
        } catch (Throwable $e) {
            $stats = ['total' => 0, 'pending' => 0, 'products' => 0];
            $recent = [];
            $this->flash('error', 'No se pudo leer la base de datos.');
        }
        $this->view('admin/dashboard', ['title' => 'Administración', 'stats' => $stats, 'recent' => $recent]);
    }

    // ---------- Productos ----------
    public function products(): void {
        $this->view('admin/products', ['title' => 'Productos', 'products' => (new Product())->adminAll()]);
    }

    public function productForm(): void {
        $id = (int)($_GET['id'] ?? 0);
        $product = null;
        if ($id) {
            $product = (new Product())->find($id);
            if (!$product) {
                $this->flash('error', 'El producto no existe.');
                $this->redirect('admin-products');
            }
        }
        $this->renderProductForm($product ?? ['id' => 0, 'nombre' => '', 'descripcion' => '', 'precio_base' => '',
            'categoria' => 'Ramos', 'tipo_flor' => 'Natural', 'imagen' => '', 'activo' => 1], []);
    }

    public function productSave(): void {
        $this->requirePost('admin-products');
        $id = (int)($_POST['id'] ?? 0);
        $products = new Product();
        $existing = $id ? $products->find($id) : null;
        if ($id && !$existing) {
            $this->flash('error', 'El producto no existe.');
            $this->redirect('admin-products');
        }
        $data = [
            'id' => $id,
            'nombre' => trim($_POST['nombre'] ?? ''),
            'descripcion' => trim($_POST['descripcion'] ?? ''),
            'precio_base' => trim($_POST['precio_base'] ?? ''),
            'categoria' => $_POST['categoria'] ?? '',
            'tipo_flor' => $_POST['tipo_flor'] ?? '',
            'imagen' => $existing['imagen'] ?? '',
            'activo' => isset($_POST['activo']) ? 1 : 0,
        ];

        $errors = [];
        if ($data['nombre'] === '' || mb_strlen($data['nombre']) > 120) { $errors[] = 'El nombre es obligatorio (máx. 120 caracteres).'; }
        if ($data['descripcion'] === '' || mb_strlen($data['descripcion']) > 255) { $errors[] = 'La descripción es obligatoria (máx. 255 caracteres).'; }
        $price = filter_var($data['precio_base'], FILTER_VALIDATE_FLOAT);
        if ($price === false || $price < 0 || $price > 99999999) { $errors[] = 'El precio debe ser un número mayor o igual a 0.'; }
        if (!in_array($data['categoria'], Product::CATEGORIES, true)) { $errors[] = 'Selecciona una categoría válida.'; }
        if (!in_array($data['tipo_flor'], Product::FLOWER_TYPES, true)) { $errors[] = 'Selecciona un tipo de flor válido.'; }

        $newImage = null;
        if (!$errors) {
            list($newImage, $uploadError) = ImageUpload::save($_FILES['imagen'] ?? null);
            if ($uploadError) { $errors[] = $uploadError; }
        }
        if ($errors) {
            $this->renderProductForm($data, $errors);
            return;
        }
        $data['precio_base'] = $price;

        $oldImage = $existing['imagen'] ?? '';
        if ($newImage) {
            $data['imagen'] = $newImage;
        } elseif (isset($_POST['remove_image'])) {
            $data['imagen'] = '';
        }

        try {
            if ($id) {
                $products->update($id, $data);
                $this->flash('success', 'Producto actualizado.');
            } else {
                $products->create($data);
                $this->flash('success', 'Producto creado.');
            }
            if ($oldImage !== '' && $oldImage !== $data['imagen']) {
                ImageUpload::delete($oldImage);
            }
        } catch (Throwable $e) {
            ImageUpload::delete($newImage);
            $this->flash('error', 'No se pudo guardar el producto.');
        }
        $this->redirect('admin-products');
    }

    public function productToggle(): void {
        $this->requirePost('admin-products');
        $id = (int)($_POST['id'] ?? 0);
        $products = new Product();
        $product = $products->find($id);
        if ($product) {
            $products->setActive($id, !$product['activo']);
            $this->flash('success', $product['activo'] ? 'Producto ocultado del catálogo.' : 'Producto visible en el catálogo.');
        }
        $this->redirect('admin-products');
    }

    public function productDelete(): void {
        $this->requirePost('admin-products');
        $id = (int)($_POST['id'] ?? 0);
        $products = new Product();
        $product = $id ? $products->find($id) : null;
        if ($product) {
            $products->delete($id);
            ImageUpload::delete($product['imagen'] ?? null);
            $this->flash('success', 'Producto eliminado.');
        }
        $this->redirect('admin-products');
    }

    // ---------- Pedidos ----------
    public function orders(): void {
        $status = $_GET['estado'] ?? '';
        $status = in_array($status, Order::STATUSES, true) ? $status : '';
        $this->view('admin/orders', [
            'title' => 'Pedidos',
            'orders' => (new Order())->all($status ?: null),
            'filter' => $status,
        ]);
    }

    public function orderStatus(): void {
        $this->requirePost('admin-orders');
        $id = (int)($_POST['id'] ?? 0);
        $status = (string)($_POST['estado'] ?? '');
        if ($id && (new Order())->updateStatus($id, $status)) {
            $this->flash('success', 'Estado del pedido actualizado.');
        } else {
            $this->flash('error', 'No se pudo actualizar el estado.');
        }
        $back = ['estado' => $_POST['filter'] ?? ''];
        $this->redirect('admin-orders', array_filter($back));
    }

    private function renderProductForm(array $product, array $errors): void {
        $this->view('admin/product_form', [
            'title' => $product['id'] ? 'Editar producto' : 'Nuevo producto',
            'product' => $product,
            'errors' => $errors,
        ]);
    }
}
