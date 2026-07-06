<?php

/**
 * Model za upravljanje proizvodima.
 * Sadrži metode za CRUD operacije i pretragu proizvoda.
 */
class Product
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
     * Preuzima sve proizvode iz baze podataka.
     *
     * @return array Niz asocijativnih nizova sa podacima o proizvodima
     */
    public function getAllProducts()
    {
        $this->db->query("SELECT p.*, c.name as category_name, b.name as brand_name 
                          FROM products p 
                          JOIN categories c ON p.category_id = c.id 
                          JOIN brands b ON p.brand_id = b.id 
                          ORDER BY p.created_at DESC");
        $this->db->execute();
        return $this->db->results();
    }

    /**
     * Preuzima jedan proizvod iz baze na osnovu ID-a.
     *
     * @param int $id ID proizvoda
     * @return array|false Asocijativni niz sa podacima o proizvodu, ili false ako ne postoji
     */
    public function getProductById($id)
    {
        $this->db->query("SELECT p.*, c.name as category_name, b.name as brand_name 
                          FROM products p 
                          JOIN categories c ON p.category_id = c.id 
                          JOIN brands b ON p.brand_id = b.id 
                          WHERE p.id = :id");
        $this->db->bind(':id', $id);
        $this->db->execute();
        return $this->db->result();
    }

    /**
     * Dodaje novi proizvod u bazu podataka.
     *
     * @param string $name Naziv proizvoda
     * @param string $description Opis proizvoda
     * @param float $price Cena proizvoda
     * @param int $stock Količina na stanju
     * @param string $image Naziv slike
     * @param int $category_id ID kategorije
     * @param int $brand_id ID brenda
     * @return bool True ako je dodavanje uspešno
     */
    public function addProduct($name, $description, $price, $stock, $image, $category_id, $brand_id)
    {
        $this->db->query("INSERT INTO products (name, description, price, stock, image, category_id, brand_id) 
                          VALUES (:name, :description, :price, :stock, :image, :category_id, :brand_id)");
        $this->db->bind(':name', $name);
        $this->db->bind(':description', $description);
        $this->db->bind(':price', $price);
        $this->db->bind(':stock', $stock);
        $this->db->bind(':image', $image);
        $this->db->bind(':category_id', $category_id);
        $this->db->bind(':brand_id', $brand_id);
        return $this->db->execute();
    }

    /**
     * Ažurira postojeći proizvod u bazi podataka.
     *
     * @param int $id ID proizvoda
     * @param string $name Naziv proizvoda
     * @param string $description Opis proizvoda
     * @param float $price Cena proizvoda
     * @param int $stock Količina na stanju
     * @param string $image Naziv slike
     * @param int $category_id ID kategorije
     * @param int $brand_id ID brenda
     * @return bool True ako je ažuriranje uspešno
     */
    public function updateProduct($id, $name, $description, $price, $stock, $image, $category_id, $brand_id)
    {
        $this->db->query("UPDATE products SET name=:name, description=:description, price=:price, 
                          stock=:stock, image=:image, category_id=:category_id, brand_id=:brand_id 
                          WHERE id=:id");
        $this->db->bind(':name', $name);
        $this->db->bind(':description', $description);
        $this->db->bind(':price', $price);
        $this->db->bind(':stock', $stock);
        $this->db->bind(':image', $image);
        $this->db->bind(':category_id', $category_id);
        $this->db->bind(':brand_id', $brand_id);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    /**
     * Briše proizvod iz baze podataka na osnovu ID-a.
     *
     * @param int $id ID proizvoda
     * @return bool True ako je brisanje uspešno
     */
    public function deleteProduct($id)
    {
        $this->db->query("DELETE FROM products WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    /**
     * Pretražuje proizvode po ključnoj reči, kategoriji i brendu sa paginacijom.
     *
     * @param string $keyword Ključna reč za pretragu
     * @param int|null $category_id ID kategorije za filtriranje
     * @param int|null $brand_id ID brenda za filtriranje
     * @param int $limit Broj proizvoda po stranici
     * @param int $offset Početni red
     * @return array Niz pronađenih proizvoda
     */
    public function searchProducts($keyword, $category_id = null, $brand_id = null, $limit = 9, $offset = 0)
    {
        $sql = "SELECT p.*, c.name as category_name, b.name as brand_name 
                FROM products p 
                JOIN categories c ON p.category_id = c.id 
                JOIN brands b ON p.brand_id = b.id 
                WHERE p.name LIKE :keyword";

        if ($category_id) {
            $sql .= " AND p.category_id = :category_id";
        }
        if ($brand_id) {
            $sql .= " AND p.brand_id = :brand_id";
        }

        $sql .= " ORDER BY p.created_at DESC LIMIT :limit OFFSET :offset";

        $this->db->query($sql);
        $this->db->bind(':keyword', '%' . $keyword . '%');
        if ($category_id) {
            $this->db->bind(':category_id', $category_id);
        }
        if ($brand_id) {
            $this->db->bind(':brand_id', $brand_id);
        }
        $this->db->bind(':limit', (int)$limit);
        $this->db->bind(':offset', (int)$offset);
        $this->db->execute();
        return $this->db->results();
    }

    /**
     * Broji ukupan broj proizvoda koji odgovaraju kriterijumima pretrage.
     *
     * @param string $keyword Ključna reč za pretragu
     * @param int|null $category_id ID kategorije za filtriranje
     * @param int|null $brand_id ID brenda za filtriranje
     * @return int Ukupan broj pronađenih proizvoda
     */
    public function countSearchProducts($keyword, $category_id = null, $brand_id = null)
    {
        $sql = "SELECT COUNT(*) as total FROM products p 
                WHERE p.name LIKE :keyword";

        if ($category_id) {
            $sql .= " AND p.category_id = :category_id";
        }
        if ($brand_id) {
            $sql .= " AND p.brand_id = :brand_id";
        }

        $this->db->query($sql);
        $this->db->bind(':keyword', '%' . $keyword . '%');
        if ($category_id) {
            $this->db->bind(':category_id', $category_id);
        }
        if ($brand_id) {
            $this->db->bind(':brand_id', $brand_id);
        }
        $this->db->execute();
        $result = $this->db->result();
        return $result['total'];
    }
}