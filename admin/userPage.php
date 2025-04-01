<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
    header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 0) {
    header('location: ../index.php');
}

require_once('../tools/functions.php');
require_once('../classes/account.class.php');
require_once('../classes/homePage.class.php');

$homePageContent = new HomePageContent();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_main'])) {
        $homePageContent->updateSection('main', $_POST['heading'], $_POST['subheading'], '');
    } elseif (isset($_POST['update_features'])) {
        $homePageContent->updateSection('features', $_POST['sectionTitle'], 'Key Features', '');

        for ($i = 1; $i <= 4; $i++) {
            $homePageContent->updateFeature($i, $_POST["icon$i"], $_POST["title$i"], $_POST["desc$i"]);
        }
    } elseif (isset($_POST['update_services'])) {
        $homePageContent->updateSection('services', $_POST['sectionTitle'], 'Our Services', '');

        for ($i = 1; $i <= 3; $i++) {
            $image_path = $_POST["existing_image$i"];
            if (!empty($_FILES["image$i"]['name'])) {
                $target_dir = "../assets/images/services/";
                $target_file = $target_dir . basename($_FILES["image$i"]["name"]);
                move_uploaded_file($_FILES["image$i"]["tmp_name"], $target_file);
                $image_path = $target_file;
            }
            $homePageContent->updateService($i, $image_path, $_POST["title$i"], $_POST["desc$i"]);
        }
    } elseif (isset($_POST['update_telemedicine'])) {
        $homePageContent->updateSection('telemedicine', $_POST['title'], $_POST['subtitle'], $_POST['footer_text']);

        $items = [
            1 => ['icon' => $_POST['icon1'], 'title' => $_POST['title1'], 'description' => $_POST['desc1']],
            2 => ['icon' => $_POST['icon2'], 'title' => $_POST['title2'], 'description' => $_POST['desc2']],
            3 => ['icon' => $_POST['icon3'], 'title' => $_POST['title3'], 'description' => $_POST['desc3']],
            4 => ['icon' => $_POST['icon4'], 'title' => $_POST['title4'], 'description' => $_POST['desc4']]
        ];

        foreach ($items as $id => $item) {
            $homePageContent->updateTelemedicine($id, $item['icon'], $item['title'], $item['description']);
        }
    } elseif (isset($_POST['update_about'])) {
        $image_path = $_POST['existing_image'];
        if (!empty($_FILES['image']['name'])) {
            $target_dir = "../assets/images/";
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
            $image_path = $target_file;
        }

        $keyPoints = implode("\n", [$_POST['keypoint1'], $_POST['keypoint2'], $_POST['keypoint3']]);

        $homePageContent->updateAboutUs(
            $_POST['subtitle'],
            $_POST['title'],
            $_POST['description'],
            $image_path,
            $keyPoints
        );
    }

    header("Location: userPage.php");
    exit();
}

$mainContent = $homePageContent->getSectionContent('main');
$featuresContent = $homePageContent->getSectionContent('features');
$servicesContent = $homePageContent->getSectionContent('services');
$telemedicineContent = $homePageContent->getSectionContent('telemedicine');
$aboutUsContent = $homePageContent->getSectionContent('about_us');

$features = $homePageContent->getFeatures();
$services = $homePageContent->getServices();
$telemedicineItems = $homePageContent->getTelemedicineItems();

$keyPoints = explode("\n", $aboutUsContent['content']);
?>
<html lang="en">
<?php
$title = 'Admin | User Page';
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

    <section id="dashboard" class="page-container">

        <?php
        $userPage = 'active';
        $aUserPage = 'page';
        $cUserPage = 'text-dark';

        include './includes/adminUserPage_nav.php';
        ?>

        <h1 class="text-start mb-3">User Home Page</h1>
        <h6 class="text-start mb-4 text-muted">Icon Class: <a href="https://boxicons.com/" target="_blank">Boxicons.com</a></h6>

        <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#main" aria-expanded="false" aria-controls="main">
            <h5 class="card-title">Main</h5>
        </button>
        <hr class="mt-1 mb-2">
        <div class="collapse" id="main">
            <div class="card mb-3 w-100">
                <div class="card-body">
                    <form class="p-2 bg-white rounded" method="POST" action="">
                        <div class="mb-3">
                            <label for="heading" class="form-label">Heading</label>
                            <input type="text" class="form-control" id="heading" name="heading" value="<?= htmlspecialchars($mainContent['title']) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="subheading" class="form-label">Subheading</label>
                            <textarea class="form-control" id="subheading" name="subheading" rows="2"><?= htmlspecialchars($mainContent['subtitle']) ?></textarea>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" name="update_main" class="btn btn-primary text-light">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#keyFeatures" aria-expanded="false" aria-controls="keyFeatures">
            <h5 class="card-title">Key Features</h5>
        </button>
        <hr class="mt-1 mb-2">
        <div class="collapse" id="keyFeatures">
            <div class="card w-100">
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="sectionTitle" class="form-label">Section Title</label>
                            <input type="text" class="form-control" id="sectionTitle" name="sectionTitle" value="<?= htmlspecialchars($featuresContent['title']) ?>">
                        </div>
                        <div class="row row-cols-1 row-cols-md-2">
                            <?php foreach ($features as $i => $feature): ?>
                                <div class="col p-0 mb-3">
                                    <div class="card mx-3 mb-sm-3 rounded-3 shadow-sm h-100">
                                        <div class="card-body d-flex flex-column justify-content-between shadow-sm text-center">
                                            <label for="icon<?= $i + 1 ?>" class="form-label">Icon Class</label>
                                            <input type="text" id="icon<?= $i + 1 ?>" name="icon<?= $i + 1 ?>" class="form-control mb-2" value="<?= htmlspecialchars($feature['icon']) ?>">
                                            <label for="title<?= $i + 1 ?>" class="form-label">Title</label>
                                            <input type="text" id="title<?= $i + 1 ?>" name="title<?= $i + 1 ?>" class="form-control mb-2" value="<?= htmlspecialchars($feature['title']) ?>">
                                            <label for="desc<?= $i + 1 ?>" class="form-label">Description</label>
                                            <textarea id="desc<?= $i + 1 ?>" name="desc<?= $i + 1 ?>" class="form-control" rows="3"><?= htmlspecialchars($feature['description']) ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" name="update_features" class="btn btn-primary text-light">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#ourServices" aria-expanded="false" aria-controls="ourServices">
            <h5 class="card-title">Our Services</h5>
        </button>
        <hr class="mt-1 mb-2">
        <div class="collapse" id="ourServices">
            <div class="card w-100">
                <div class="card-body">
                    <form method="POST" action="" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="sectionTitle" class="form-label">Section Title</label>
                            <input type="text" class="form-control" id="sectionTitle" name="sectionTitle" value="<?= htmlspecialchars($servicesContent['title']) ?>">
                        </div>

                        <div class="row row-cols-1 row-cols-md-3 g-4 mb-3">
                            <?php foreach ($services as $i => $service): ?>
                                <div class="col">
                                    <div class="card h-100 p-2">
                                        <label for="image<?= $i + 1 ?>" class="form-label">Upload Image <?= $i + 1 ?></label>
                                        <input type="file" class="form-control" id="image<?= $i + 1 ?>" name="image<?= $i + 1 ?>" onchange="previewImage(event, 'preview<?= $i + 1 ?>')">
                                        <input type="hidden" name="existing_image<?= $i + 1 ?>" value="<?= htmlspecialchars($service['image_path']) ?>">
                                        <img id="preview<?= $i + 1 ?>" src="<?= htmlspecialchars($service['image_path']) ?>" class="card-img-top" style="height: 250px; object-fit: cover;">
                                        <div class="card-body">
                                            <label for="title<?= $i + 1 ?>" class="form-label">Title</label>
                                            <input type="text" class="form-control" id="title<?= $i + 1 ?>" name="title<?= $i + 1 ?>" value="<?= htmlspecialchars($service['title']) ?>">
                                            <label for="desc<?= $i + 1 ?>" class="form-label">Description</label>
                                            <textarea class="form-control" id="desc<?= $i + 1 ?>" name="desc<?= $i + 1 ?>" rows="3"><?= htmlspecialchars($service['description']) ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" name="update_services" class="btn btn-primary text-light">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#information" aria-expanded="false" aria-controls="information">
            <h5 class="card-title">Information</h5>
        </button>
        <hr class="mt-1 mb-2">
        <div class="collapse" id="information">
            <div class="card w-100">
                <div class="card-body">
                    <form method="POST" action="">
                        <h2 class="mb-3">Edit Telemedicine Content</h2>

                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" class="form-control" name="title" value="<?= htmlspecialchars($telemedicineContent['title']) ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Subtitle</label>
                            <textarea class="form-control" name="subtitle" rows="2"><?= htmlspecialchars($telemedicineContent['subtitle']) ?></textarea>
                        </div>

                        <h4 class="mt-4">Services</h4>

                        <?php foreach ($telemedicineItems as $i => $item): ?>
                            <div class="card p-3 mb-3">
                                <div class="mb-3">
                                    <label class="form-label">Icon class</label>
                                    <input type="text" class="form-control" name="icon<?= $i + 1 ?>" value="<?= htmlspecialchars($item['icon']) ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Title</label>
                                    <input type="text" class="form-control" name="title<?= $i + 1 ?>" value="<?= htmlspecialchars($item['title']) ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="desc<?= $i + 1 ?>" rows="3"><?= htmlspecialchars($item['description']) ?></textarea>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <div class="mb-3">
                            <label class="form-label">Footer Text</label>
                            <textarea class="form-control" name="footer_text" rows="2"><?= htmlspecialchars($telemedicineContent['content']) ?></textarea>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" name="update_telemedicine" class="btn btn-primary text-light">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#about" aria-expanded="false" aria-controls="about">
            <h5 class="card-title">About Us</h5>
        </button>
        <hr class="mt-1 mb-2">
        <div class="collapse" id="about">
            <div class="card w-100">
                <div class="card-body">
                    <div class="container mb-4 pt-2">
                        <form method="POST" action="" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="subtitle" class="form-label text-secondary">Subtitle</label>
                                <input type="text" class="form-control" id="subtitle" name="subtitle" value="<?= htmlspecialchars($aboutUsContent['subtitle']) ?>">
                            </div>
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($aboutUsContent['title']) ?>">
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3"><?= htmlspecialchars($aboutUsContent['description']) ?></textarea>
                            </div>

                            <label class="form-label">Key Points</label>
                            <?php for ($i = 0; $i < 3; $i++): ?>
                                <div class="mb-3 d-flex align-items-center">
                                    <input type="text" class="form-control" name="keypoint<?= $i + 1 ?>" value="<?= htmlspecialchars($keyPoints[$i] ?? '') ?>">
                                </div>
                            <?php endfor; ?>

                            <div class="mb-3">
                                <label for="image" class="form-label">Upload Image</label>
                                <input type="file" class="form-control" id="image" name="image" onchange="previewImage(event, 'preview4')">
                                <input type="hidden" name="existing_image" value="<?= htmlspecialchars($aboutUsContent['image_path']) ?>">
                                <img id="preview4" src="<?= htmlspecialchars($aboutUsContent['image_path']) ?>" class="card-img-top mt-2" style="height: 250px; object-fit: cover;">
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" name="update_about" class="btn btn-primary text-light">Save</button>
                            </div>
                        </form>
                    </div>
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