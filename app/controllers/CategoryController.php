<?php

class CategoryController extends Controller
{
    public function index()
    {
        $this->requireAdmin();
        $categoryModel = $this->loadModel('Category');
        $this->renderView('categories/index', [
            'categories' => $categoryModel->getAllCategories()
        ], 'Kategorije');
    }

    public function add()
    {
        $this->requireAdmin();
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            $description = trim($_POST['description']);

            if (empty($name)) {
                $error = 'Naziv kategorije je obavezan.';
            } else {
                $categoryModel = $this->loadModel('Category');
                $categoryModel->addCategory($name, $description);
                header('Location: ' . BASE_URL . 'categories');
                exit;
            }
        }

        $this->renderView('categories/add', ['error' => $error], 'Dodaj kategoriju');
    }

    public function update($id)
    {
        $this->requireAdmin();
        $this->validateId($id);

        $categoryModel = $this->loadModel('Category');
        $category = $categoryModel->getCategoryById($id);

        if (!$category) {
            http_response_code(404);
            echo "<h1>404 - Kategorija nije pronađena</h1>";
            exit;
        }

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            $description = trim($_POST['description']);

            if (empty($name)) {
                $error = 'Naziv kategorije je obavezan.';
            } else {
                $categoryModel->updateCategory($id, $name, $description);
                header('Location: ' . BASE_URL . 'categories');
                exit;
            }
        }

        $this->renderView('categories/update', [
            'category' => $category,
            'error' => $error
        ], 'Izmeni kategoriju');
    }

    public function delete($id)
    {
        $this->requireAdmin();
        $this->validateId($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $categoryModel = $this->loadModel('Category');
            $categoryModel->deleteCategory($id);
        }

        header('Location: ' . BASE_URL . 'categories');
        exit;
    }
}