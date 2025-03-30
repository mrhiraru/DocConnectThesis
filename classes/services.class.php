<?php
require_once('database.php');

class Services
{
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->connect();
    }

    // Get all sections with their services
    public function getAllSectionsWithServices()
    {
        $stmt = $this->db->prepare("
            SELECT * FROM services 
            WHERE is_active = TRUE 
            ORDER BY section_id, display_order
        ");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Organize by sections
        $sections = [];
        foreach ($result as $row) {
            $sectionId = $row['section_id'];
            if (!isset($sections[$sectionId])) {
                $sections[$sectionId] = [
                    'section_title' => $row['section_title'],
                    'section_description' => $row['section_description'],
                    'services' => []
                ];
            }
            if ($row['service_title']) {
                $sections[$sectionId]['services'][] = [
                    'title' => $row['service_title'],
                    'description' => $row['service_description'],
                    'image' => $row['service_image']
                ];
            }
        }

        return array_values($sections);
    }

    // Get about section content
    public function getAboutContent()
    {
        $stmt = $this->db->prepare("
            SELECT * FROM services 
            WHERE section_id = 0 AND is_active = TRUE
            LIMIT 1
        ");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Add or update a section
    public function saveSection($sectionId, $title, $description)
    {
        // Check if section exists
        $stmt = $this->db->prepare("
            SELECT id FROM services 
            WHERE section_id = :section_id 
            AND service_title IS NULL 
            LIMIT 1
        ");
        $stmt->execute([':section_id' => $sectionId]);
        $existing = $stmt->fetch();

        if ($existing) {
            // Update existing section
            $stmt = $this->db->prepare("
                UPDATE services SET
                    section_title = :title,
                    section_description = :description,
                    updated_at = NOW()
                WHERE id = :id
            ");
            return $stmt->execute([
                ':title' => $title,
                ':description' => $description,
                ':id' => $existing['id']
            ]);
        } else {
            // Insert new section
            $stmt = $this->db->prepare("
                INSERT INTO services (
                    section_id, 
                    section_title, 
                    section_description
                ) VALUES (
                    :section_id, 
                    :title, 
                    :description
                )
            ");
            return $stmt->execute([
                ':section_id' => $sectionId,
                ':title' => $title,
                ':description' => $description
            ]);
        }
    }

    // Add or update a service
    public function saveService($sectionId, $serviceData, $serviceIndex)
    {
        // Check if service exists
        $stmt = $this->db->prepare("
            SELECT id FROM services 
            WHERE section_id = :section_id 
            AND service_title IS NOT NULL
            ORDER BY id
            LIMIT 1 OFFSET :offset
        ");
        $stmt->bindValue(':section_id', $sectionId, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $serviceIndex, PDO::PARAM_INT);
        $stmt->execute();
        $existing = $stmt->fetch();

        if ($existing) {
            // Update existing service
            $stmt = $this->db->prepare("
                UPDATE services SET
                    service_title = :title,
                    service_description = :description,
                    service_image = :image,
                    display_order = :order,
                    updated_at = NOW()
                WHERE id = :id
            ");
            return $stmt->execute([
                ':title' => $serviceData['title'],
                ':description' => $serviceData['description'],
                ':image' => $serviceData['image'],
                ':order' => $serviceIndex,
                ':id' => $existing['id']
            ]);
        } else {
            // Insert new service
            $stmt = $this->db->prepare("
                INSERT INTO services (
                    section_id,
                    section_title,
                    service_title,
                    service_description,
                    service_image,
                    display_order
                ) VALUES (
                    :section_id,
                    :section_title,
                    :title,
                    :description,
                    :image,
                    :order
                )
            ");
            return $stmt->execute([
                ':section_id' => $sectionId,
                ':section_title' => $serviceData['section_title'],
                ':title' => $serviceData['title'],
                ':description' => $serviceData['description'],
                ':image' => $serviceData['image'],
                ':order' => $serviceIndex
            ]);
        }
    }

    // Delete a section and its services
    public function deleteSection($sectionId)
    {
        $stmt = $this->db->prepare("
            UPDATE services 
            SET is_active = FALSE 
            WHERE section_id = :section_id
        ");
        return $stmt->execute([':section_id' => $sectionId]);
    }

    // Delete a specific service
    public function deleteService($serviceId)
    {
        $stmt = $this->db->prepare("
            UPDATE services 
            SET is_active = FALSE 
            WHERE id = :id
        ");
        return $stmt->execute([':id' => $serviceId]);
    }

    // Save about section content
    public function saveAboutContent($title, $description)
    {
        // Section ID 0 is reserved for about content
        return $this->saveSection(0, $title, $description);
    }
}
?>