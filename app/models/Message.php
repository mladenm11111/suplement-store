<?php

class Message
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAllMessages()
    {
        $this->db->query("SELECT m.*, u.username, u.email 
                          FROM messages m 
                          JOIN users u ON m.user_id = u.id 
                          ORDER BY m.created_at DESC");
        $this->db->execute();
        return $this->db->results();
    }

    public function getMessagesByUser($user_id)
    {
        $this->db->query("SELECT * FROM messages WHERE user_id = :user_id ORDER BY created_at DESC");
        $this->db->bind(':user_id', $user_id);
        $this->db->execute();
        return $this->db->results();
    }

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

    public function sendMessage($user_id, $subject, $message)
    {
        $this->db->query("INSERT INTO messages (user_id, subject, message) 
                          VALUES (:user_id, :subject, :message)");
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':subject', $subject);
        $this->db->bind(':message', $message);
        return $this->db->execute();
    }

    public function replyToMessage($id, $response)
    {
        $this->db->query("UPDATE messages SET response = :response WHERE id = :id");
        $this->db->bind(':response', $response);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function deleteMessage($id)
    {
        $this->db->query("DELETE FROM messages WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
