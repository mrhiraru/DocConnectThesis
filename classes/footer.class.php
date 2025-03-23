<?php
require_once('database.php');

class FooterContent
{
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->connect();
    }

    public function getFooterContent()
    {
        $stmt = $this->db->prepare("SELECT * FROM footer_content ORDER BY last_updated DESC LIMIT 1");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateFooterContent($description, $address, $phone_numbers, $facebook_link, $gmail_link)
    {
        $stmt = $this->db->prepare("INSERT INTO footer_content (description, address, phone_numbers, facebook_link, gmail_link) VALUES (:description, :address, :phone_numbers, :facebook_link, :gmail_link)");
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':phone_numbers', $phone_numbers);
        $stmt->bindParam(':facebook_link', $facebook_link);
        $stmt->bindParam(':gmail_link', $gmail_link);
        return $stmt->execute();
    }
}
?>