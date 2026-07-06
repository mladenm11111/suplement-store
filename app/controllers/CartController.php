<?php

class CartController extends Controller
{
    public function index()
    {
        $this->requireLogin();

        $cartModel = $this->loadModel('Cart');
        $productModel = $this->loadModel('Product');

        $cartItems = $cartModel->getCart();
        $products = [];
        $total = 0;

        foreach ($cartItems as $product_id => $quantity) {
            $product = $productModel->getProductById($product_id);
            if ($product) {
                $product['quantity'] = $quantity;
                $product['subtotal'] = $product['price'] * $quantity;
                $total += $product['subtotal'];
                $products[] = $product;
            }
        }

        $this->renderView('cart/index', [
            'products' => $products,
            'total' => $total
        ], 'Korpa');
    }

    public function add($product_id)
    {
        $this->requireLogin();
        $this->validateId($product_id);

        $cartModel = $this->loadModel('Cart');
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
        $cartModel->addToCart($product_id, $quantity);

        header('Location: ' . BASE_URL . 'cart');
        exit;
    }

    public function remove($product_id)
    {
        $this->requireLogin();
        $this->validateId($product_id);

        $cartModel = $this->loadModel('Cart');
        $cartModel->removeFromCart($product_id);

        header('Location: ' . BASE_URL . 'cart');
        exit;
    }

    public function checkout()
    {
        $this->requireLogin();

        $cartModel = $this->loadModel('Cart');
        $productModel = $this->loadModel('Product');
        $orderModel = $this->loadModel('Order');

        $cartItems = $cartModel->getCart();

        if (empty($cartItems)) {
            header('Location: ' . BASE_URL . 'cart');
            exit;
        }

        $total = 0;
        foreach ($cartItems as $product_id => $quantity) {
            $product = $productModel->getProductById($product_id);
            if ($product) {
                $total += $product['price'] * $quantity;
            }
        }

        $order_id = $orderModel->createOrder($_SESSION['user_id'], $total);

        foreach ($cartItems as $product_id => $quantity) {
            $product = $productModel->getProductById($product_id);
            if ($product) {
                $orderModel->addOrderItem($order_id, $product_id, $quantity, $product['price']);
            }
        }

        $cartModel->clearCart();

        $this->log('Korisnik kreirao narudžbinu #' . $order_id);
        header('Location: ' . BASE_URL . 'orders');
        exit;
    }
}
