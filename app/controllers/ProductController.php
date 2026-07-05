<?php

class ProductController extends Controller
{
    public function index()
    {
        $productModel = $this->loadModel('Product');

        $keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
        $category_id = isset($_GET['category_id']) ? $_GET['category_id'] : null;
        $brand_id = isset($_GET['brand_id']) ? $_GET['brand_id'] : null;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 9;
        $offset = ($page - 1) * $limit;

        $products = $productModel->searchProducts($keyword, $category_id, $brand_id, $limit, $offset);
        $total = $productModel->countSearchProducts($keyword, $category_id, $brand_id);
        $totalPages = ceil($total / $limit);

        $categoryModel = $this->loadModel('Category');
        $brandModel = $this->loadModel('Brand');

        $this->renderView('products/index', [
            'products' => $products,
            'categories' => $categoryModel->getAllCategories(),
            'brands' => $brandModel->getAllBrands(),
            'keyword' => $keyword,
            'category_id' => $category_id,
            'brand_id' => $brand_id,
            'page' => $page,
            'totalPages' => $totalPages
        ], 'Proizvodi');
    }

    public function show($id)
    {
        $this->validateId($id);
        $productModel = $this->loadModel('Product');
        $product = $productModel->getProductById($id);

        if (!$product) {
            http_response_code(404);
            echo "<h1>404 - Proizvod nije pronađen</h1>";
            exit;
        }

        $this->renderView('products/show', ['product' => $product], $product['name']);
    }

    public function add()
    {
        $this->requireAdmin();

        $categoryModel = $this->loadModel('Category');
        $brandModel = $this->loadModel('Brand');
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            $description = trim($_POST['description']);
            $price = trim($_POST['price']);
            $stock = trim($_POST['stock']);
            $category_id = $_POST['category_id'];
            $brand_id = $_POST['brand_id'];
            $image = '';

            if (empty($name) || empty($description) || empty($price) || empty($stock)) {
                $error = 'Sva polja su obavezna.';
            } else {
                if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                    $uploadDir = '../public/uploads/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    $imageName = time() . '_' . basename($_FILES['image']['name']);
                    move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $imageName);
                    $image = $imageName;
                }

                $productModel = $this->loadModel('Product');
                $productModel->addProduct($name, $description, $price, $stock, $image, $category_id, $brand_id);
                $this->log('Admin dodao novi proizvod: ' . $name);
                header('Location: ' . BASE_URL . 'products');
                exit;
            }
        }

        $this->renderView('products/add', [
            'categories' => $categoryModel->getAllCategories(),
            'brands' => $brandModel->getAllBrands(),
            'error' => $error
        ], 'Dodaj proizvod');
    }

    public function update($id)
    {
        $this->requireAdmin();
        $this->validateId($id);

        $productModel = $this->loadModel('Product');
        $product = $productModel->getProductById($id);

        if (!$product) {
            http_response_code(404);
            echo "<h1>404 - Proizvod nije pronađen</h1>";
            exit;
        }

        $categoryModel = $this->loadModel('Category');
        $brandModel = $this->loadModel('Brand');
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            $description = trim($_POST['description']);
            $price = trim($_POST['price']);
            $stock = trim($_POST['stock']);
            $category_id = $_POST['category_id'];
            $brand_id = $_POST['brand_id'];
            $image = $product['image'];

            if (empty($name) || empty($description) || empty($price) || empty($stock)) {
                $error = 'Sva polja su obavezna.';
            } else {
                if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                    $uploadDir = '../public/uploads/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    $imageName = time() . '_' . basename($_FILES['image']['name']);
                    move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $imageName);
                    $image = $imageName;
                }

                $productModel->updateProduct($id, $name, $description, $price, $stock, $image, $category_id, $brand_id);
                header('Location: ' . BASE_URL . 'products');
                exit;
            }
        }

        $this->renderView('products/update', [
            'product' => $product,
            'categories' => $categoryModel->getAllCategories(),
            'brands' => $brandModel->getAllBrands(),
            'error' => $error
        ], 'Izmeni proizvod');
    }

    public function delete($id)
    {
        $this->requireAdmin();
        $this->validateId($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productModel = $this->loadModel('Product');
            $productModel->deleteProduct($id);
        }

        header('Location: ' . BASE_URL . 'products');
        exit;
    }
}