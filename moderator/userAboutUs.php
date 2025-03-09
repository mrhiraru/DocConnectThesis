<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
    header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 0) {
    header('location: ../index.php');
}

require_once('../tools/functions.php');
require_once('../classes/account.class.php');

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

        include './includes/adminUserPage_nav.php';
        ?>

        <h1 class="text-start mb-3">User About us</h1>
        <h6 class="text-start mb-4 text-muted">Icon Class: <a href="https://boxicons.com/" target="_blank">Boxicons.com</a></h6>

        <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#aboutSection" aria-expanded="false" aria-controls="aboutSection">
            <h5 class="card-title">About Section</h5>
        </button>
        <hr class="mt-1 mb-2">
        <div class="collapse" id="aboutSection">
            <div class="card mb-3 w-100">
                <div class="card-body">
                    <?php
                    session_start();

                    // Initialize temporary storage if not set
                    if (!isset($_SESSION['visions'])) {
                        $_SESSION['visions'] = [
                            "Revolutionize healthcare accessibility through telecommunication.",
                            "Ensure every student receives quality healthcare, regardless of location.",
                            "Promote a culture of proactive health and wellness among students.",
                            "Integrate cutting-edge technology to provide seamless healthcare experiences.",
                            "Create a healthier, more connected university community."
                        ];
                    }

                    if (!isset($_SESSION['missions'])) {
                        $_SESSION['missions'] = [
                            "Provide accessible telehealth services to university students worldwide.",
                            "Empower students with tools and resources for better health management.",
                            "Offer comprehensive mental and physical healthcare solutions.",
                            "Foster innovation in telemedicine to improve healthcare delivery.",
                            "Maintain the highest standards of privacy, security, and care quality."
                        ];
                    }

                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $heading = $_POST['heading'];
                        $subtext = $_POST['subtext'];

                        if (isset($_POST['delete_vision'])) {
                            unset($_SESSION['visions'][$_POST['delete_vision']]);
                            $_SESSION['visions'] = array_values($_SESSION['visions']);
                        } elseif (isset($_POST['delete_mission'])) {
                            unset($_SESSION['missions'][$_POST['delete_mission']]);
                            $_SESSION['missions'] = array_values($_SESSION['missions']);
                        } elseif (isset($_POST['add_vision'])) {
                            $_SESSION['visions'][] = "";
                        } elseif (isset($_POST['add_mission'])) {
                            $_SESSION['missions'][] = "";
                        } else {
                            $_SESSION['visions'] = array_filter($_POST['visions'] ?? []);
                            $_SESSION['missions'] = array_filter($_POST['missions'] ?? []);
                        }

                        // Handle file upload
                        if (!empty($_FILES['imageUpload']['name'])) {
                            $targetDir = "uploads/";
                            $targetFile = $targetDir . basename($_FILES["imageUpload"]["name"]);
                            move_uploaded_file($_FILES["imageUpload"]["tmp_name"], $targetFile);
                        }
                    }
                    ?>

                    <form class="row my-5" method="POST" enctype="multipart/form-data">
                        <div class="col-12 col-md-12">
                            <div class="mb-3">
                                <label for="heading" class="form-label">Heading</label>
                                <input type="text" class="form-control" name="heading" value="<?= htmlspecialchars($heading ?? 'Your Health, Anytime, Anywhere') ?>">
                            </div>

                            <div class="mb-3">
                                <label for="subtext" class="form-label">Subtext</label>
                                <textarea class="form-control" name="subtext" rows="3"><?= htmlspecialchars($subtext ?? 'Welcome to University Telecommunications Health Services! We are dedicated to enhancing student well-being through innovative, remote health solutions that ensure accessibility, privacy, and high-quality care.') ?></textarea>
                            </div>

                            <div class="row row-cols-1 row-cols-md-2">
                                <div class="col">
                                    <h3>Our Vision:</h3>
                                    <ul class="list-unstyled">
                                        <?php foreach ($_SESSION['visions'] as $index => $vision): ?>
                                            <li class="d-flex align-items-baseline mb-2">
                                                <input type="text" class="form-control" name="visions[]" value="<?= htmlspecialchars($vision) ?>">
                                                <button type="submit" name="delete_mission" value="<?= $index ?>" class="btn btn-link btn-sm ms-2"><i class='bx bx-trash fs-5'></i></button>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                    <button type="submit" name="add_vision" class="btn btn-success btn-sm">Add Vision</button>
                                </div>
                                <div class="col">
                                    <h3>Our Mission:</h3>
                                    <ul class="list-unstyled">
                                        <?php foreach ($_SESSION['missions'] as $index => $mission): ?>
                                            <li class="d-flex align-items-baseline mb-2">
                                                <input type="text" class="form-control" name="missions[]" value="<?= htmlspecialchars($mission) ?>">
                                                <button type="submit" name="delete_mission" value="<?= $index ?>" class="btn btn-link btn-sm ms-2"><i class='bx bx-trash fs-5'></i></button>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                    <button type="submit" name="add_mission" class="btn btn-success btn-sm">Add Mission</button>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 d-none d-md-block">
                            <div class="h-100 rounded-2 d-flex flex-column align-items-center justify-content-center overflow-hidden">
                                <label for="imageUpload" class="form-label">Upload Image</label>
                                <input class="form-control" type="file" name="imageUpload">
                                <img src="<?= $targetFile ?? '../assets/images/bg-1.png' ?>" alt="Image" class="img-fluid mt-2" style="height: 50%; width: 50%; object-fit: cover;">
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
        function previewImage(event, previewId) {
            const reader = new FileReader();
            reader.onload = function() {
                document.getElementById(previewId).src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>

</body>

</html>