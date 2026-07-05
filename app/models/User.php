<?php

class User
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function register($username, $email, $password)
    {
        $this->db->query("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $this->db->bind(':username', $username);
        $this->db->bind(':email', $email);
        $this->db->bind(':password', $password);
        return $this->db->execute();
    }

    public function getUserByEmail($email)
    {
        $this->db->query("SELECT * FROM users WHERE email = :email");
        $this->db->bind(':email', $email);
        $this->db->execute();
        return $this->db->result();
    }

    public function getUserById($id)
    {
        $this->db->query("SELECT * FROM users WHERE id = :id");
        $this->db->bind(':id', $id);
        $this->db->execute();
        return $this->db->result();
    }

    public function getAllUsers()
    {
        $this->db->query("SELECT * FROM users ORDER BY created_at DESC");
        $this->db->execute();
        return $this->db->results();
    }

    public function deleteUser($id)
    {
        $this->db->query("DELETE FROM users WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function updateUser($id, $username, $email, $role)
    {
        $this->db->query("UPDATE users SET username = :username, email = :email, role = :role WHERE id = :id");
        $this->db->bind(':username', $username);
        $this->db->bind(':email', $email);
        $this->db->bind(':role', $role);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function emailExists($email)
    {
        $this->db->query("SELECT id FROM users WHERE email = :email");
        $this->db->bind(':email', $email);
        $this->db->execute();
        return $this->db->rowCount() > 0;
    }
}