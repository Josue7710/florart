<?php
class CatalogController extends Controller {
    public function index(): void {
        try {
            $products = (new Product())->all();
        } catch (Throwable $e) {
            $products = [];
            $this->flash('error', 'No se pudo cargar el catálogo. Revisa la conexión con la base de datos.');
        }
        $this->view('catalog', ['title' => 'Catálogo', 'products' => $products]);
    }
}
