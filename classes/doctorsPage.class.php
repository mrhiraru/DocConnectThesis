<?php
require_once('database.php');

class DoctorsPage
{
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->connect();
    }

    public function getSectionContent($sectionName)
    {
        $stmt = $this->db->prepare("SELECT * FROM doctorsPage_content WHERE section_name = :section_name");
        $stmt->bindParam(':section_name', $sectionName);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateIntro($title, $content)
    {
        $stmt = $this->db->prepare("UPDATE doctorsPage_content SET title = :title, content = :content WHERE section_name = 'intro'");
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        return $stmt->execute();
    }

    public function updateSpecializations($title, $subtitle, $spec1Title, $spec1Content, $spec2Title, $spec2Content, $spec3Title, $spec3Content)
    {
        $stmt = $this->db->prepare("UPDATE doctorsPage_content SET 
            title = :title, 
            content = :content,
            spec1_title = :spec1_title,
            spec1_content = :spec1_content,
            spec2_title = :spec2_title,
            spec2_content = :spec2_content,
            spec3_title = :spec3_title,
            spec3_content = :spec3_content
            WHERE section_name = 'specializations'");

        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $subtitle);
        $stmt->bindParam(':spec1_title', $spec1Title);
        $stmt->bindParam(':spec1_content', $spec1Content);
        $stmt->bindParam(':spec2_title', $spec2Title);
        $stmt->bindParam(':spec2_content', $spec2Content);
        $stmt->bindParam(':spec3_title', $spec3Title);
        $stmt->bindParam(':spec3_content', $spec3Content);

        return $stmt->execute();
    }

    public function updateTelehealth($title, $content, $imagePath, $quote, $quoteAuthor)
    {
        $stmt = $this->db->prepare("UPDATE doctorsPage_content SET 
            title = :title, 
            content = :content,
            image_path = :image_path,
            quote = :quote,
            quote_author = :quote_author
            WHERE section_name = 'telehealth'");

        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':image_path', $imagePath);
        $stmt->bindParam(':quote', $quote);
        $stmt->bindParam(':quote_author', $quoteAuthor);

        return $stmt->execute();
    }

    public function updateCommunity($title, $content, $imagePath)
    {
        $stmt = $this->db->prepare("UPDATE doctorsPage_content SET 
            title = :title, 
            content = :content,
            image_path = :image_path
            WHERE section_name = 'community'");

        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':image_path', $imagePath);

        return $stmt->execute();
    }

    public function updateAccessibility($title, $content, $imagePath)
    {
        $stmt = $this->db->prepare("UPDATE doctorsPage_content SET 
            title = :title, 
            content = :content,
            image_path = :image_path
            WHERE section_name = 'accessibility'");

        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':image_path', $imagePath);

        return $stmt->execute();
    }

    public function uploadImage($file, $targetDir = "../assets/images/")
    {
        if (!empty($file['name'])) {
            $fileName = basename($file['name']);
            $targetFile = $targetDir . $fileName;

            $check = getimagesize($file['tmp_name']);
            if ($check === false) {
                throw new Exception('File is not an image.');
            }

            if ($file['size'] > 5000000) {
                throw new Exception('Sorry, your file is too large.');
            }

            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
            if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                throw new Exception('Sorry, only JPG, JPEG, PNG & GIF files are allowed.');
            }

            if (move_uploaded_file($file['tmp_name'], $targetFile)) {
                return $fileName;
            } else {
                throw new Exception('Sorry, there was an error uploading your file.');
            }
        }
        throw new Exception('No file uploaded.');
    }
}
