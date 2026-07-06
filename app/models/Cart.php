<?php

/**
 * Model za upravljanje korpom.
 * Koristi PHP sesije za čuvanje stavki korpe.
 */
class Cart
{
    /**
     * Dodaje proizvod u korpu ili povećava količinu ako već postoji.
     *
     * @param int $product_id ID proizvoda
     * @param int $quantity Količina
     * @return void
     */
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

    /**
     * Uklanja proizvod iz korpe.
     *
     * @param int $product_id ID proizvoda
     * @return void
     */
    public function removeFromCart($product_id)
    {
        if (isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);
        }
    }

    /**
     * Preuzima sve stavke iz korpe.
     *
     * @return array Asocijativni niz sa ID-ovima proizvoda i količinama
     */
    public function getCart()
    {
        return isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
    }

    /**
     * Prazni korpu.
     *
     * @return void
     */
    public function clearCart()
    {
        $_SESSION['cart'] = [];
    }

    /**
     * Preuzima ukupan broj stavki u korpi.
     *
     * @return int Ukupan broj stavki
     */
    public function getItemCount()
    {
        if (!isset($_SESSION['cart'])) {
            return 0;
        }
        return array_sum($_SESSION['cart']);
    }
}