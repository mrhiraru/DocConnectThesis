<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
    header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 0) {
    header('location: ../index.php');
}

require_once('../classes/aboutUs.class.php');

$aboutUs = new AboutUs();
$currentAboutUs = $aboutUs->getAboutUs();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $heading = $_POST['heading'];
    $subtext = $_POST['subtext'];
    $visions = $_POST['visions'] ?? [];
    $missions = $_POST['missions'] ?? [];
    $technology_heading = $_POST['technology_heading'];
    $technology_subtext = $_POST['technology_subtext'];
    $technology_icons = $_POST['technology_icons'] ?? [];
    $technology_titles = $_POST['technology_titles'] ?? [];
    $technology_descriptions = $_POST['technology_descriptions'] ?? [];

    $image_path = $currentAboutUs['image_path'] ?? '';
    if (!empty($_FILES['imageUpload']['name'])) {
        $targetDir = "../assets/images/about_us/";
        $targetFile = $targetDir . basename($_FILES["imageUpload"]["name"]);

        // Check if the file is an image
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($imageFileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES["imageUpload"]["tmp_name"], $targetFile)) {
                $image_path = $targetFile;
            } else {
                $_SESSION['error'] = 'Failed to upload image.';
            }
        } else {
            $_SESSION['error'] = 'Only JPG, JPEG, PNG, and GIF files are allowed.';
        }
    }

    if ($aboutUs->updateAboutUs($heading, $subtext, $visions, $missions, $image_path, $technology_heading, $technology_subtext, $technology_icons, $technology_titles, $technology_descriptions)) {
        $_SESSION['message'] = 'About Us updated successfully!';
        header('location: settingAboutUs.php');
        exit();
    } else {
        $_SESSION['error'] = 'Failed to update About Us.';
    }
}

?>
<html lang="en">
<?php
$title = 'Admin | User About Us';
include './includes/admin_head.php';
function getCurrentPage()
{
    return basename($_SERVER['PHP_SELF']);
}
?>

<body>
    <?php
    require_once('./includes/admin_header.php');
    ?>
    <?php
    require_once('./includes/admin_sidepanel.php');
    ?>

    <section id="userPage" class="page-container">

        <?php
        $aboutUs = 'active';
        $aAboutUs = 'page';
        $cAboutUs = 'text-dark';

        include './includes/adminSettings_nav.php';
        ?>

        <h1 class="text-start mb-3">User About us</h1>
        <h6 class="text-start mb-4 text-muted">Icon Class: <a href="https://boxicons.com/" target="_blank">Boxicons.com</a></h6>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['message'];
                                                unset($_SESSION['message']); ?></div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['error'];
                                            unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#aboutSection" aria-expanded="false" aria-controls="aboutSection">
            <div class="d-flex flex-row align-items-center">
                <h5 class="card-title">About Section</h5>
                <i id="chevronIconAbout" class='bx bxs-chevron-down ms-2'></i>
            </div>
        </button>
        <hr class="mt-1 mb-2">
        <div class="collapse" id="aboutSection">
            <div class="card mb-3 w-100">
                <div class="card-body">
                    <form class="row" method="POST" enctype="multipart/form-data">
                        <div class="col-12 col-md-8">
                            <div class="mb-3">
                                <label for="heading" class="form-label">Heading</label>
                                <input type="text" class="form-control" name="heading" value="<?= htmlspecialchars($currentAboutUs['heading'] ?? 'Your Health, Anytime, Anywhere') ?>">
                            </div>

                            <div class="mb-3">
                                <label for="subtext" class="form-label">Subtext</label>
                                <textarea class="form-control" name="subtext" rows="3"><?= htmlspecialchars($currentAboutUs['subtext'] ?? 'Welcome to University Telecommunications Health Services! We are dedicated to enhancing student well-being through innovative, remote health solutions that ensure accessibility, privacy, and high-quality care.') ?></textarea>
                            </div>

                            <div class="row row-cols-1 row-cols-md-2">
                                <div class="col">
                                    <h3>Our Vision:</h3>
                                    <ul class="list-unstyled">
                                        <?php foreach (json_decode($currentAboutUs['visions'] ?? '[]') as $index => $vision): ?>
                                            <li class="d-flex align-items-baseline mb-2">
                                                <input type="text" class="form-control" name="visions[]" value="<?= htmlspecialchars($vision) ?>">
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                    <button type="button" class="btn btn-success btn-sm text-light" onclick="addVision()">Add Vision</button>
                                </div>
                                <div class="col">
                                    <h3>Our Mission:</h3>
                                    <ul class="list-unstyled">
                                        <?php foreach (json_decode($currentAboutUs['missions'] ?? '[]') as $index => $mission): ?>
                                            <li class="d-flex align-items-baseline mb-2">
                                                <input type="text" class="form-control" name="missions[]" value="<?= htmlspecialchars($mission) ?>">
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                    <button type="button" class="btn btn-success btn-sm text-light" onclick="addMission()">Add Mission</button>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-4">
                            <div class="h-100 rounded-2 d-flex flex-column align-items-center justify-content-center overflow-hidden">
                                <label for="imageUpload" class="form-label">Upload Image</label>
                                <input class="form-control" type="file" name="imageUpload">
                                <img src="<?= $currentAboutUs['image_path'] ?? '../assets/images/bg-1.png' ?>" alt="Image" class="img-fluid mt-2 h-50 w-100" style="object-fit: cover;">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary text-light">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#secondSection" aria-expanded="false" aria-controls="secondSection">
            <div class="d-flex flex-row align-items-center">
                <h5 class="card-title">Technology and Innovation</h5>
                <i id="chevronIconTech" class='bx bxs-chevron-down ms-2'></i>
            </div>
        </button>
        <hr class="mt-1 mb-2">
        <div class="collapse" id="secondSection">
            <div class="card w-100">
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label for="technology_heading" class="form-label">Heading</label>
                            <input type="text" class="form-control" name="technology_heading" value="<?= htmlspecialchars($currentAboutUs['technology_heading'] ?? 'Technology and Innovation') ?>">
                        </div>
                        <div class="mb-3">
                            <label for="technology_subtext" class="form-label">Subheading</label>
                            <textarea class="form-control" name="technology_subtext" rows="2"><?= htmlspecialchars($currentAboutUs['technology_subtext'] ?? 'Using cutting-edge telecommunication tools, we ensure secure, reliable, and seamless interactions between students and healthcare providers.') ?></textarea>
                        </div>
                        <div class="row row-cols-1 row-cols-md-2">
                            <?php
                            $technology_icons = json_decode($currentAboutUs['technology_icons'] ?? '[]');
                            $technology_titles = json_decode($currentAboutUs['technology_titles'] ?? '[]');
                            $technology_descriptions = json_decode($currentAboutUs['technology_descriptions'] ?? '[]');

                            for ($i = 0; $i < 4; $i++): ?>
                                <div class="col p-0 mb-3">
                                    <div class="card mx-3 mb-sm-3 rounded-3 shadow-sm h-100">
                                        <div class="card-body d-flex flex-column justify-content-between shadow-sm text-center">
                                            <label for="icon<?= $i + 1 ?>" class="form-label">Icon Class</label>
                                            <input type="text" class="form-control mb-2" name="technology_icons[]" value="<?= htmlspecialchars($technology_icons[$i] ?? '') ?>">
                                            <label for="title<?= $i + 1 ?>" class="form-label">Title</label>
                                            <input type="text" class="form-control mb-2" name="technology_titles[]" value="<?= htmlspecialchars($technology_titles[$i] ?? '') ?>">
                                            <label for="desc<?= $i + 1 ?>" class="form-label">Description</label>
                                            <textarea class="form-control" name="technology_descriptions[]" rows="3"><?= htmlspecialchars($technology_descriptions[$i] ?? '') ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            <?php endfor; ?>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary text-light">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // About Section
            const aboutButton = document.querySelector('[data-bs-target="#aboutSection"]');
            const chevronIconAbout = document.getElementById('chevronIconAbout');

            aboutButton.addEventListener('click', function() {
                chevronIconAbout.classList.toggle('rotate-180');
            });

            // Technology and Innovation Section
            const techButton = document.querySelector('[data-bs-target="#secondSection"]');
            const chevronIconTech = document.getElementById('chevronIconTech');

            techButton.addEventListener('click', function() {
                chevronIconTech.classList.toggle('rotate-180');
            });
        });
        
        function addVision() {
            const visionList = document.querySelector('ul:has(input[name="visions[]"])');
            const newVision = document.createElement('li');
            newVision.className = 'd-flex align-items-baseline mb-2';
            newVision.innerHTML = `<input type="text" class="form-control" name="visions[]">`;
            visionList.appendChild(newVision);
        }

        function addMission() {
            const missionList = document.querySelector('ul:has(input[name="missions[]"])');
            const newMission = document.createElement('li');
            newMission.className = 'd-flex align-items-baseline mb-2';
            newMission.innerHTML = `<input type="text" class="form-control" name="missions[]">`;
            missionList.appendChild(newMission);
        }
    </script>

</body>

</html>