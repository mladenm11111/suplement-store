<?php

/**
 * Model za upravljanje kategorijama proizvoda.
 * Sadrži metode za CRUD operacije nad kategorijama.
 */
class Category
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
     * Preuzima sve kategorije iz baze podataka.
     *
     * @return array Niz asocijativnih nizova sa podacima o kategorijama
     */
    public function getAllCategories()
    {
        $this->db->query("SELECT * FROM categories ORDER BY name ASC");
        $this->db->execute();
        return $this->db->results();
    }

    /**
     * Preuzima jednu kategoriju iz baze na osnovu ID-a.
     *
     * @param int $id ID kategorije
     * @return array|false Asocijativni niz sa podacima o kategoriji, ili false ako ne postoji
     */
    public function getCategoryById($id)
    {
        $this->db->query("SELECT * FROM categories WHERE id = :id");
        $this->db->bind(':id', $id);
        $this->db->execute();
        return $this->db->result();
    }

    /**
     * Dodaje novu kategoriju u bazu podataka.
     *
     * @param string $name Naziv kategorije
     * @param string $description Opis kategorije
     * @return bool True ako je dodavanje uspešno
     */
    public function addCategory($name, $description)
    {
        $this->db->query("INSERT INTO categories (name, description) VALUES (:name, :description)");
        $this->db->bind(':name', $name);
        $this->db->bind(':description', $description);
        return $this->db->execute();
    }

    /**
     * Ažurira postojeću kategoriju u bazi podataka.
     *
     * @param int $id ID kategorije
     * @param string $name Naziv kategorije
     * @param string $description Opis kategorije
     * @return bool True ako je ažuriranje uspešno
     */
    public function updateCategory($id, $name, $description)
    {
        $this->db->query("UPDATE categories SET name=:name, description=:description WHERE id=:id");
        $this->db->bind(':name', $name);
        $this->db->bind(':description', $description);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    /**
     * Briše kategoriju iz baze podataka na osnovu ID-a.
     *
     * @param int $id ID kategorije
     * @return bool True ako je brisanje uspešno
     */
    public function deleteCategory($id)
    {
        $this->db->query("DELETE FROM categories WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}