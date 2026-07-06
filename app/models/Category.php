<?php

class Category
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAllCategories()
    {
        $this->db->query("SELECT * FROM categories ORDER BY name ASC");
        $this->db->execute();
        return $this->db->results();
    }

    public function getCategoryById($id)
    {
        $this->db->query("SELECT * FROM categories WHERE id = :id");
        $this->db->bind(':id', $id);
        $this->db->execute();
        return $this->db->result();
    }

    public function addCategory($name, $description)
    {
        $this->db->query("INSERT INTO categories (name, description) VALUES (:name, :description)");
        $this->db->bind(':name', $name);
        $this->db->bind(':description', $description);
        return $this->db->execute();
    }

    public function updateCategory($id, $name, $description)
    {
        $this->db->query("UPDATE categories SET name=:name, description=:description WHERE id=:id");
        $this->db->bind(':name', $name);
        $this->db->bind(':description', $description);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function deleteCategory($id)
    {
        $this->db->query("DELETE FROM categories WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
