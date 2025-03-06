<?php
require_once('database.php');

class AboutUs
{
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->connect();
    }

    public function getAboutUs()
    {
        $stmt = $this->db->prepare("SELECT * FROM about_us ORDER BY last_updated DESC LIMIT 1");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateAboutUs($heading, $subtext, $visions, $missions, $image_path, $technology_heading, $technology_subtext)
    {
        $visions = json_encode($visions);
        $missions = json_encode($missions);

        $stmt = $this->db->prepare("INSERT INTO about_us (heading, subtext, visions, missions, image_path, technology_heading, technology_subtext) VALUES (:heading, :subtext, :visions, :missions, :image_path, :technology_heading, :technology_subtext)");
        $stmt->bindParam(':heading', $heading);
        $stmt->bindParam(':subtext', $subtext);
        $stmt->bindParam(':visions', $visions);
        $stmt->bindParam(':missions', $missions);
        $stmt->bindParam(':image_path', $image_path);
        $stmt->bindParam(':technology_heading', $technology_heading);
        $stmt->bindParam(':technology_subtext', $technology_subtext);
        return $stmt->execute();
    }
}
?>