<?php

/**
 * Model za upravljanje narudžbinama.
 * Sadrži metode za kreiranje, pregled i ažuriranje narudžbina.
 */
class Order
{
    /**
     * @var Database Instanca klase za komunikaciju sa bazom podataka
     */
    private $db;

    /**
     * Inicijalizuje konekciju na bazu podataka.
     */
    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Kreira novu narudžbinu u bazi podataka.
     *
     * @param int $user_id ID korisnika
     * @param float $total_price Ukupna cena narudžbine
     * @return string ID kreirane narudžbine
     */
    public function createOrder($user_id, $total_price)
    {
        $this->db->query("INSERT INTO orders (user_id, total_price) VALUES (:user_id, :total_price)");
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':total_price', $total_price);
        $this->db->execute();
        return $this->db->lastInsertId();
    }

    /**
     * Dodaje stavku u narudžbinu.
     *
     * @param int $order_id ID narudžbine
     * @param int $product_id ID proizvoda
     * @param int $quantity Količina
     * @param float $price Cena proizvoda
     * @return bool True ako je dodavanje uspešno
     */
    public function addOrderItem($order_id, $product_id, $quantity, $price)
    {
        $this->db->query("INSERT INTO order_items (order_id, product_id, quantity, price) 
                          VALUES (:order_id, :product_id, :quantity, :price)");
        $this->db->bind(':order_id', $order_id);
        $this->db->bind(':product_id', $product_id);
        $this->db->bind(':quantity', $quantity);
        $this->db->bind(':price', $price);
        return $this->db->execute();
    }

    /**
     * Preuzima sve narudžbine iz baze podataka.
     *
     * @return array Niz asocijativnih nizova sa podacima o narudžbinama
     */
    public function getAllOrders()
    {
        $this->db->query("SELECT o.*, u.username, u.email 
                          FROM orders o 
                          JOIN users u ON o.user_id = u.id 
                          ORDER BY o.created_at DESC");
        $this->db->execute();
        return $this->db->results();
    }

    /**
     * Preuzima sve narudžbine jednog korisnika.
     *
     * @param int $user_id ID korisnika
     * @return array Niz asocijativnih nizova sa podacima o narudžbinama
     */
    public function getOrdersByUser($user_id)
    {
        $this->db->query("SELECT * FROM orders WHERE user_id = :user_id ORDER BY created_at DESC");
        $this->db->bind(':user_id', $user_id);
        $this->db->execute();
        return $this->db->results();
    }

    /**
     * Preuzima jednu narudžbinu iz baze na osnovu ID-a.
     *
     * @param int $id ID narudžbine
     * @return array|false Asocijativni niz sa podacima o narudžbini, ili false ako ne postoji
     */
    public function getOrderById($id)
    {
        $this->db->query("SELECT o.*, u.username, u.email 
                          FROM orders o 
                          JOIN users u ON o.user_id = u.id 
                          WHERE o.id = :id");
        $this->db->bind(':id', $id);
        $this->db->execute();
        return $this->db->result();
    }

    /**
     * Preuzima sve stavke jedne narudžbine.
     *
     * @param int $order_id ID narudžbine
     * @return array Niz asocijativnih nizova sa stavkama narudžbine
     */
    public function getOrderItems($order_id)
    {
        $this->db->query("SELECT oi.*, p.name as product_name 
                          FROM order_items oi 
                          JOIN products p ON oi.product_id = p.id 
                          WHERE oi.order_id = :order_id");
        $this->db->bind(':order_id', $order_id);
        $this->db->execute();
        return $this->db->results();
    }

    /**
     * Ažurira status narudžbine.
     *
     * @param int $id ID narudžbine
     * @param string $status Novi status narudžbine
     * @return bool True ako je ažuriranje uspešno
     */
    public function updateOrderStatus($id, $status)
    {
        $this->db->query("UPDATE orders SET status = :status WHERE id = :id");
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    /**
     * Briše narudžbinu i sve njene stavke iz baze podataka.
     *
     * @param int $id ID narudžbine
     * @return bool True ako je brisanje uspešno
     */
    public function deleteOrder($id)
    {
        $this->db->query("DELETE FROM order_items WHERE order_id = :id");
        $this->db->bind(':id', $id);
        $this->db->execute();

        $this->db->query("DELETE FROM orders WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}