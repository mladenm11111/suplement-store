<?php

/**
 * Model za upravljanje brendovima.
 * Sadrži metode za CRUD operacije nad brendovima.
 */
class Brand
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
     * Preuzima sve brendove iz baze podataka.
     *
     * @return array Niz asocijativnih nizova sa podacima o brendovima
     */
    public function getAllBrands()
    {
        $this->db->query("SELECT * FROM brands ORDER BY name ASC");
        $this->db->execute();
        return $this->db->results();
    }

    /**
     * Preuzima jedan brend iz baze na osnovu ID-a.
     *
     * @param int $id ID brenda
     * @return array|false Asocijativni niz sa podacima o brendu, ili false ako ne postoji
     */
    public function getBrandById($id)
    {
        $this->db->query("SELECT * FROM brands WHERE id = :id");
        $this->db->bind(':id', $id);
        $this->db->execute();
        return $this->db->result();
    }

    /**
     * Dodaje novi brend u bazu podataka.
     *
     * @param string $name Naziv brenda
     * @param string $description Opis brenda
     * @return bool True ako je dodavanje uspešno
     */
    public function addBrand($name, $description)
    {
        $this->db->query("INSERT INTO brands (name, description) VALUES (:name, :description)");
        $this->db->bind(':name', $name);
        $this->db->bind(':description', $description);
        return $this->db->execute();
    }

    /**
     * Ažurira postojeći brend u bazi podataka.
     *
     * @param int $id ID brenda
     * @param string $name Naziv brenda
     * @param string $description Opis brenda
     * @return bool True ako je ažuriranje uspešno
     */
    public function updateBrand($id, $name, $description)
    {
        $this->db->query("UPDATE brands SET name=:name, description=:description WHERE id=:id");
        $this->db->bind(':name', $name);
        $this->db->bind(':description', $description);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    /**
     * Briše brend iz baze podataka na osnovu ID-a.
     *
     * @param int $id ID brenda
     * @return bool True ako je brisanje uspešno
     */
    public function deleteBrand($id)
    {
        $this->db->query("DELETE FROM brands WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}