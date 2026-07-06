<?php

class Brand
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAllBrands()
    {
        $this->db->query("SELECT * FROM brands ORDER BY name ASC");
        $this->db->execute();
        return $this->db->results();
    }

    public function getBrandById($id)
    {
        $this->db->query("SELECT * FROM brands WHERE id = :id");
        $this->db->bind(':id', $id);
        $this->db->execute();
        return $this->db->result();
    }

    public function addBrand($name, $description)
    {
        $this->db->query("INSERT INTO brands (name, description) VALUES (:name, :description)");
        $this->db->bind(':name', $name);
        $this->db->bind(':description', $description);
        return $this->db->execute();
    }

    public function updateBrand($id, $name, $description)
    {
        $this->db->query("UPDATE brands SET name=:name, description=:description WHERE id=:id");
        $this->db->bind(':name', $name);
        $this->db->bind(':description', $description);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function deleteBrand($id)
    {
        $this->db->query("DELETE FROM brands WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
