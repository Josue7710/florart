<?php
class HomeController extends Controller {
    public function index(): void {
        try {
            $featured = array_slice((new Product())->all(), 0, 4);
        } catch (Throwable $e) {
            $featured = [];
        }
        $this->view('home', ['title' => 'Inicio', 'featured' => $featured]);
    }
    public function notFound(): void {
        $this->view('not_found', ['title' => 'Página no encontrada']);
    }
    public function serverError(): void {
        $this->view('error', ['title' => 'Error del servidor', 'heading' => 'Algo salió mal',
            'text' => 'Ocurrió un error inesperado. Inténtalo de nuevo en unos minutos.']);
    }
}
