<?php

class Cart
{
    public function addToCart($product_id, $quantity = 1)
    {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = $quantity;
        }
    }

    public function removeFromCart($product_id)
    {
        if (isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);
        }
    }

    public function getCart()
    {
        return isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
    }

    public function clearCart()
    {
        $_SESSION['cart'] = [];
    }

    public function getItemCount()
    {
        if (!isset($_SESSION['cart'])) {
            return 0;
        }
        return array_sum($_SESSION['cart']);
    }
}
