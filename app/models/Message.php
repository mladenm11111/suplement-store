<?php

/**
 * Model za upravljanje porukama korisnika.
 * Sadrži metode za slanje, pregled i odgovaranje na poruke.
 */
class Message
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
     * Preuzima sve poruke iz baze podataka.
     *
     * @return array Niz asocijativnih nizova sa podacima o porukama
     */
    public function getAllMessages()
    {
        $this->db->query("SELECT m.*, u.username, u.email 
                          FROM messages m 
                          JOIN users u ON m.user_id = u.id 
                          ORDER BY m.created_at DESC");
        $this->db->execute();
        return $this->db->results();
    }

    /**
     * Preuzima sve poruke jednog korisnika.
     *
     * @param int $user_id ID korisnika
     * @return array Niz asocijativnih nizova sa podacima o porukama
     */
    public function getMessagesByUser($user_id)
    {
        $this->db->query("SELECT * FROM messages WHERE user_id = :user_id ORDER BY created_at DESC");
        $this->db->bind(':user_id', $user_id);
        $this->db->execute();
        return $this->db->results();
    }

    /**
     * Preuzima jednu poruku iz baze na osnovu ID-a.
     *
     * @param int $id ID poruke
     * @return array|false Asocijativni niz sa podacima o poruci, ili false ako ne postoji
     */
    public function getMessageById($id)
    {
        $this->db->query("SELECT m.*, u.username, u.email 
                          FROM messages m 
                          JOIN users u ON m.user_id = u.id 
                          WHERE m.id = :id");
        $this->db->bind(':id', $id);
        $this->db->execute();
        return $this->db->result();
    }

    /**
     * Šalje novu poruku u bazu podataka.
     *
     * @param int $user_id ID korisnika koji šalje poruku
     * @param string $subject Naslov poruke
     * @param string $message Sadržaj poruke
     * @return bool True ako je slanje uspešno
     */
    public function sendMessage($user_id, $subject, $message)
    {
        $this->db->query("INSERT INTO messages (user_id, subject, message) 
                          VALUES (:user_id, :subject, :message)");
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':subject', $subject);
        $this->db->bind(':message', $message);
        return $this->db->execute();
    }

    /**
     * Dodaje odgovor na poruku.
     *
     * @param int $id ID poruke
     * @param string $response Odgovor na poruku
     * @return bool True ako je odgovor uspešno dodat
     */
    public function replyToMessage($id, $response)
    {
        $this->db->query("UPDATE messages SET response = :response WHERE id = :id");
        $this->db->bind(':response', $response);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    /**
     * Briše poruku iz baze podataka na osnovu ID-a.
     *
     * @param int $id ID poruke
     * @return bool True ako je brisanje uspešno
     */
    public function deleteMessage($id)
    {
        $this->db->query("DELETE FROM messages WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}