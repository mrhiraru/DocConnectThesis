<?php
require_once('database.php');

class PrivacyPolicy
{
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->connect();
    }

    public function getPrivacyPolicy()
    {
        $stmt = $this->db->prepare("SELECT content FROM privacy_policy ORDER BY last_updated DESC LIMIT 1");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updatePrivacyPolicy($content)
    {
        $stmt = $this->db->prepare("INSERT INTO privacy_policy (content) VALUES (:content)");
        $stmt->bindParam(':content', $content);
        return $stmt->execute();
    }
}
?>