<?php
require_once('database.php');

class HomeContent
{
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->connect();
    }

    // Get the latest home content
    public function getHomeContent()
    {
        $stmt = $this->db->prepare("SELECT * FROM home_content ORDER BY last_updated DESC LIMIT 1");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update home content
    public function updateHomeContent($heading, $subheading, $key_features, $services, $telemedicine, $about_us)
    {
        $key_features = json_encode($key_features);
        $services = json_encode($services);
        $telemedicine = json_encode($telemedicine);
        $about_us = json_encode($about_us);

        $stmt = $this->db->prepare("INSERT INTO home_content (heading, subheading, key_features, services, telemedicine, about_us) VALUES (:heading, :subheading, :key_features, :services, :telemedicine, :about_us)");
        $stmt->bindParam(':heading', $heading);
        $stmt->bindParam(':subheading', $subheading);
        $stmt->bindParam(':key_features', $key_features);
        $stmt->bindParam(':services', $services);
        $stmt->bindParam(':telemedicine', $telemedicine);
        $stmt->bindParam(':about_us', $about_us);
        return $stmt->execute();
    }
}
?>