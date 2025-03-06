<?php
require_once('database.php');

class TermsOfService
{
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->connect();
    }

    public function getTermsOfService()
    {
        $stmt = $this->db->prepare("SELECT content FROM terms_of_service ORDER BY last_updated DESC LIMIT 1");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateTermsOfService($content)
    {
        $stmt = $this->db->prepare("INSERT INTO terms_of_service (content) VALUES (:content)");
        $stmt->bindParam(':content', $content);
        return $stmt->execute();
    }
}
?>