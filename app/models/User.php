<?php

/**
 * Model za upravljanje korisnicima.
 * Sadrži metode za registraciju, prijavu i CRUD operacije nad korisnicima.
 */
class User
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
     * Registruje novog korisnika u bazi podataka.
     *
     * @param string $username Korisničko ime
     * @param string $email Email adresa
     * @param string $password Hashovana lozinka
     * @return bool True ako je registracija uspešna
     */
    public function register($username, $email, $password)
    {
        $this->db->query("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $this->db->bind(':username', $username);
        $this->db->bind(':email', $email);
        $this->db->bind(':password', $password);
        return $this->db->execute();
    }

    /**
     * Preuzima korisnika iz baze na osnovu email adrese.
     *
     * @param string $email Email adresa korisnika
     * @return array|false Asocijativni niz sa podacima o korisniku, ili false ako ne postoji
     */
    public function getUserByEmail($email)
    {
        $this->db->query("SELECT * FROM users WHERE email = :email");
        $this->db->bind(':email', $email);
        $this->db->execute();
        return $this->db->result();
    }

    /**
     * Preuzima korisnika iz baze na osnovu ID-a.
     *
     * @param int $id ID korisnika
     * @return array|false Asocijativni niz sa podacima o korisniku, ili false ako ne postoji
     */
    public function getUserById($id)
    {
        $this->db->query("SELECT * FROM users WHERE id = :id");
        $this->db->bind(':id', $id);
        $this->db->execute();
        return $this->db->result();
    }

    /**
     * Preuzima sve korisnike iz baze podataka.
     *
     * @return array Niz asocijativnih nizova sa podacima o korisnicima
     */
    public function getAllUsers()
    {
        $this->db->query("SELECT * FROM users ORDER BY created_at DESC");
        $this->db->execute();
        return $this->db->results();
    }

    /**
     * Briše korisnika iz baze podataka na osnovu ID-a.
     *
     * @param int $id ID korisnika
     * @return bool True ako je brisanje uspešno
     */
    public function deleteUser($id)
    {
        $this->db->query("DELETE FROM users WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    /**
     * Ažurira podatke korisnika u bazi podataka.
     *
     * @param int $id ID korisnika
     * @param string $username Korisničko ime
     * @param string $email Email adresa
     * @param string $role Rola korisnika
     * @return bool True ako je ažuriranje uspešno
     */
    public function updateUser($id, $username, $email, $role)
    {
        $this->db->query("UPDATE users SET username = :username, email = :email, role = :role WHERE id = :id");
        $this->db->bind(':username', $username);
        $this->db->bind(':email', $email);
        $this->db->bind(':role', $role);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    /**
     * Proverava da li email adresa već postoji u bazi podataka.
     *
     * @param string $email Email adresa
     * @return bool True ako email već postoji
     */
    public function emailExists($email)
    {
        $this->db->query("SELECT id FROM users WHERE email = :email");
        $this->db->bind(':email', $email);
        $this->db->execute();
        return $this->db->rowCount() > 0;
    }
}