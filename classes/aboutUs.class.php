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

    public function updateAboutUs($heading, $subtext, $visions, $missions, $image_path, $technology_heading, $technology_subtext, $technology_icons, $technology_titles, $technology_descriptions)
    {
        $visions = json_encode($visions);
        $missions = json_encode($missions);
        $technology_icons = json_encode($technology_icons);
        $technology_titles = json_encode($technology_titles);
        $technology_descriptions = json_encode($technology_descriptions);

        $stmt = $this->db->prepare("INSERT INTO about_us (heading, subtext, visions, missions, image_path, technology_heading, technology_subtext, technology_icons, technology_titles, technology_descriptions) VALUES (:heading, :subtext, :visions, :missions, :image_path, :technology_heading, :technology_subtext, :technology_icons, :technology_titles, :technology_descriptions)");
        $stmt->bindParam(':heading', $heading);
        $stmt->bindParam(':subtext', $subtext);
        $stmt->bindParam(':visions', $visions);
        $stmt->bindParam(':missions', $missions);
        $stmt->bindParam(':image_path', $image_path);
        $stmt->bindParam(':technology_heading', $technology_heading);
        $stmt->bindParam(':technology_subtext', $technology_subtext);
        $stmt->bindParam(':technology_icons', $technology_icons);
        $stmt->bindParam(':technology_titles', $technology_titles);
        $stmt->bindParam(':technology_descriptions', $technology_descriptions);
        return $stmt->execute();
    }
}
?>