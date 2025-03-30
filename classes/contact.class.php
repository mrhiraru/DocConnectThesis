<?php
require_once('database.php');

class ContactInfo
{
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->connect();
    }

    public function getContactInfo()
    {
        $stmt = $this->db->prepare("SELECT * FROM contact_us ORDER BY last_updated DESC LIMIT 1");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateContactInfo($data)
    {
        $stmt = $this->db->prepare("
            UPDATE contact_us SET
                heading = :heading,
                description = :description,
                address = :address,
                email = :email,
                phone = :phone,
                fax = :fax,
                facebook_link = :facebook_link,
                instagram_link = :instagram_link,
                map_embed_url = :map_embed_url
            WHERE id = (SELECT id FROM (SELECT id FROM contact_us ORDER BY last_updated DESC LIMIT 1) as temp)
        ");
        
        return $stmt->execute([
            ':heading' => $data['heading'],
            ':description' => $data['description'],
            ':address' => $data['address'],
            ':email' => $data['email'],
            ':phone' => $data['phone'],
            ':fax' => $data['fax'],
            ':facebook_link' => $data['facebook_link'],
            ':instagram_link' => $data['instagram_link'],
            ':map_embed_url' => $data['map_embed_url']
        ]);
    }
}
?>