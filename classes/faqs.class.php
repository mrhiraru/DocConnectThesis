<?php
require_once('database.php');

class FAQ
{
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->connect();
    }

    // Get all FAQs
    public function getFAQs()
    {
        $stmt = $this->db->prepare("SELECT * FROM faqs ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get a single FAQ by ID
    public function getFAQById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM faqs WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Add a new FAQ
    public function addFAQ($question, $answer)
    {
        $stmt = $this->db->prepare("INSERT INTO faqs (question, answer) VALUES (:question, :answer)");
        $stmt->bindParam(':question', $question);
        $stmt->bindParam(':answer', $answer);
        return $stmt->execute();
    }

    // Update an existing FAQ
    public function updateFAQ($id, $question, $answer)
    {
        $stmt = $this->db->prepare("UPDATE faqs SET question = :question, answer = :answer WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':question', $question);
        $stmt->bindParam(':answer', $answer);
        return $stmt->execute();
    }

    // Delete an FAQ
    public function deleteFAQ($id)
    {
        $stmt = $this->db->prepare("DELETE FROM faqs WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>