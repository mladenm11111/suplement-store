<?php

class OrderController extends Controller
{
    public function index()
    {
        $this->requireLogin();
        $orderModel = $this->loadModel('Order');

        if ($this->isAdmin() || $this->isManager()) {
            $orders = $orderModel->getAllOrders();
        } else {
            $orders = $orderModel->getOrdersByUser($_SESSION['user_id']);
        }

        $this->renderView('orders/index', [
            'orders' => $orders
        ], 'Narudžbine');
    }

    public function show($id)
    {
        $this->requireLogin();
        $this->validateId($id);

        $orderModel = $this->loadModel('Order');
        $order = $orderModel->getOrderById($id);

        if (!$order) {
            http_response_code(404);
            echo "<h1>404 - Narudžbina nije pronađena</h1>";
            exit;
        }

        if (!$this->isAdmin() && !$this->isManager() && $order['user_id'] != $_SESSION['user_id']) {
            http_response_code(403);
            echo "<h1>403 - Nemate pristup ovoj stranici</h1>";
            exit;
        }

        $items = $orderModel->getOrderItems($id);

        $this->renderView('orders/show', [
            'order' => $order,
            'items' => $items
        ], 'Detalji narudžbine');
    }

    public function update($id)
    {
        $this->requireManager();
        $this->validateId($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderModel = $this->loadModel('Order');
            $status = $_POST['status'];
            $orderModel->updateOrderStatus($id, $status);
        }

        header('Location: ' . BASE_URL . 'orders');
        exit;
    }

    public function delete($id)
    {
        $this->requireAdmin();
        $this->validateId($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderModel = $this->loadModel('Order');
            $orderModel->deleteOrder($id);
        }

        header('Location: ' . BASE_URL . 'orders');
        exit;
    }
}
