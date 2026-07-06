<?php

class BrandController extends Controller
{
    public function index()
    {
        $this->requireAdmin();
        $brandModel = $this->loadModel('Brand');
        $this->renderView('brands/index', [
            'brands' => $brandModel->getAllBrands()
        ], 'Brendovi');
    }

    public function add()
    {
        $this->requireAdmin();
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            $description = trim($_POST['description']);

            if (empty($name)) {
                $error = 'Naziv brenda je obavezan.';
            } else {
                $brandModel = $this->loadModel('Brand');
                $brandModel->addBrand($name, $description);
                header('Location: ' . BASE_URL . 'brands');
                exit;
            }
        }

        $this->renderView('brands/add', ['error' => $error], 'Dodaj brend');
    }

    public function update($id)
    {
        $this->requireAdmin();
        $this->validateId($id);

        $brandModel = $this->loadModel('Brand');
        $brand = $brandModel->getBrandById($id);

        if (!$brand) {
            http_response_code(404);
            echo "<h1>404 - Brend nije pronađen</h1>";
            exit;
        }

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            $description = trim($_POST['description']);

            if (empty($name)) {
                $error = 'Naziv brenda je obavezan.';
            } else {
                $brandModel->updateBrand($id, $name, $description);
                header('Location: ' . BASE_URL . 'brands');
                exit;
            }
        }

        $this->renderView('brands/update', [
            'brand' => $brand,
            'error' => $error
        ], 'Izmeni brend');
    }

    public function delete($id)
    {
        $this->requireAdmin();
        $this->validateId($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $brandModel = $this->loadModel('Brand');
            $brandModel->deleteBrand($id);
        }

        header('Location: ' . BASE_URL . 'brands');
        exit;
    }
}
