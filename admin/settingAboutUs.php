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

    $image_path = $currentAboutUs['image_path'] ?? '';
    if (!empty($_FILES['imageUpload']['name'])) {
        $targetDir = "../assets/images/about_us";
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

    if ($aboutUs->updateAboutUs($heading, $subtext, $visions, $missions, $image_path, $technology_heading, $technology_subtext)) {
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
            <h5 class="card-title">About Section</h5>
        </button>
        <hr class="mt-1 mb-2">
        <div class="collapse show" id="aboutSection">
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
                                    <button type="button" class="btn btn-success btn-sm" onclick="addVision()">Add Vision</button>
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
                                    <button type="button" class="btn btn-success btn-sm" onclick="addMission()">Add Mission</button>
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
            <h5 class="card-title">Second section</h5>
        </button>
        <hr class="mt-1 mb-2">
        <div class="collapse" id="secondSection">
            <div class="card w-100">
                <div class="card-body">
                    <form>
                        <div class="mb-3">
                            <label for="heading" class="form-label">Heading</label>
                            <input type="text" class="form-control" id="heading" value="Technology and Innovation">
                        </div>
                        <div class="mb-3">
                            <label for="subheading" class="form-label">Subheading</label>
                            <textarea class="form-control" id="subheading" rows="2">Using cutting-edge telecommunication tools, we ensure secure, reliable, and seamless interactions between students and healthcare providers.</textarea>
                        </div>
                        <div class="row row-cols-1 row-cols-md-2">
                            <div class="col p-0 mb-3">
                                <div class="card mx-3 mb-sm-3 rounded-3 shadow-sm h-100">
                                    <div class="card-body d-flex flex-column justify-content-between shadow-sm text-center">
                                        <label for="icon1" class="form-label">Icon Class</label>
                                        <input type="text" id="icon1" class="form-control mb-2" value="bxs-heart">
                                        <label for="title1" class="form-label">Title</label>
                                        <input type="text" id="title1" class="form-control mb-2" value="Enhanced Patient Engagement and Satisfaction">
                                        <label for="desc1" class="form-label">Description</label>
                                        <textarea id="desc1" class="form-control" rows="3">Empower patients to participate in their healthcare journey through telecommunication health services.</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col p-0 mb-3">
                                <div class="card mx-3 mb-sm-3 rounded-3 shadow-sm h-100">
                                    <div class="card-body d-flex flex-column justify-content-between shadow-sm text-center">
                                        <label for="icon2" class="form-label">Icon Class</label>
                                        <input type="text" id="icon2" class="form-control mb-2" value="bx-phone-call">
                                        <label for="title2" class="form-label">Title</label>
                                        <input type="text" id="title2" class="form-control mb-2" value="Remote Consultations">
                                        <label for="desc2" class="form-label">Description</label>
                                        <textarea id="desc2" class="form-control" rows="3">Access healthcare professionals from anywhere, eliminating the need for in-person visits.</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col p-0 mb-3">
                                <div class="card mx-3 mb-sm-3 rounded-3 shadow-sm h-100">
                                    <div class="card-body d-flex flex-column justify-content-between shadow-sm text-center">
                                        <label for="icon3" class="form-label">Icon Class</label>
                                        <input type="text" id="icon3" class="form-control mb-2" value="bxs-user-voice">
                                        <label for="title3" class="form-label">Title</label>
                                        <input type="text" id="title3" class="form-control mb-2" value="Specialized Telehealth Services">
                                        <label for="desc3" class="form-label">Description</label>
                                        <textarea id="desc3" class="form-control" rows="3">Collaborative care coordination between your primary care provider and specialists for comprehensive treatment plans.</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col p-0 mb-3">
                                <div class="card mx-3 mb-sm-3 rounded-3 shadow-sm h-100">
                                    <div class="card-body d-flex flex-column justify-content-between shadow-sm text-center">
                                        <label for="icon4" class="form-label">Icon Class</label>
                                        <input type="text" id="icon4" class="form-control mb-2" value="bxs-buildings">
                                        <label for="title4" class="form-label">Title</label>
                                        <input type="text" id="title4" class="form-control mb-2" value="Scalable Telehealth Solutions for Healthcare Providers">
                                        <label for="desc4" class="form-label">Description</label>
                                        <textarea id="desc4" class="form-control" rows="3">Customizable telehealth platforms tailored to the needs of individual healthcare practices.</textarea>
                                    </div>
                                </div>
                            </div>
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