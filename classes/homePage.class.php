<?php
require_once('database.php');

class HomePageContent
{
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->connect();
    }

    public function getSections()
    {
        $stmt = $this->db->prepare("SELECT * FROM homePage_content");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get content by section name
    public function getSectionContent($section_name)
    {
        $stmt = $this->db->prepare("SELECT * FROM homePage_content WHERE section_name = :section_name");
        $stmt->bindParam(':section_name', $section_name);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update section content
    public function updateSection($section_name, $title, $subtitle, $content)
    {
        $stmt = $this->db->prepare("UPDATE homePage_content SET title = :title, subtitle = :subtitle, content = :content WHERE section_name = :section_name");
        $stmt->bindParam(':section_name', $section_name);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':subtitle', $subtitle);
        $stmt->bindParam(':content', $content);
        return $stmt->execute();
    }

    // Update feature content
    public function updateFeature($id, $icon, $title, $description)
    {
        $stmt = $this->db->prepare("UPDATE homePage_features SET icon = :icon, title = :title, description = :description WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':icon', $icon);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        return $stmt->execute();
    }

    // Get all features
    public function getFeatures()
    {
        $stmt = $this->db->prepare("SELECT * FROM homePage_features ORDER BY id");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update service content
    public function updateService($id, $image_path, $title, $description)
    {
        $stmt = $this->db->prepare("UPDATE homePage_services SET image_path = :image_path, title = :title, description = :description WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':image_path', $image_path);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        return $stmt->execute();
    }

    // Get all services
    public function getServices()
    {
        $stmt = $this->db->prepare("SELECT * FROM homePage_services ORDER BY id");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update telemedicine content
    public function updateTelemedicine($id, $icon, $title, $description)
    {
        $stmt = $this->db->prepare("UPDATE homePage_telemedicine SET icon = :icon, title = :title, description = :description WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':icon', $icon);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        return $stmt->execute();
    }

    // Get all telemedicine items
    public function getTelemedicineItems()
    {
        $stmt = $this->db->prepare("SELECT * FROM homePage_telemedicine ORDER BY id");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // About Us Section Methods
    public function getAboutUsContent()
    {
        $stmt = $this->db->prepare("SELECT * FROM homepage_aboutus LIMIT 1");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateAboutUs($subtitle, $title, $description, $image_path, $key_points)
    {
        // Check if record exists
        $existing = $this->getAboutUsContent();

        if ($existing) {
            // Update existing record
            $stmt = $this->db->prepare("UPDATE homepage_aboutus SET 
            subtitle = :subtitle,
            title = :title,
            description = :description,
            image_path = :image_path,
            key_points = :key_points
            WHERE id = :id");

            $stmt->bindParam(':id', $existing['id']);
        } else {
            // Insert new record
            $stmt = $this->db->prepare("INSERT INTO homepage_aboutus 
            (subtitle, title, description, image_path, key_points) 
            VALUES 
            (:subtitle, :title, :description, :image_path, :key_points)");
        }

        $stmt->bindParam(':subtitle', $subtitle);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':image_path', $image_path);
        $stmt->bindParam(':key_points', $key_points);

        return $stmt->execute();
    }
}
