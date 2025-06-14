<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
    header('location: ../user/verification.php');
    exit();
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 0) {
    header('location: ../index.php');
    exit();
}

require_once('../classes/services.class.php');
$services = new Services();
$aboutContent = $services->getAboutContent();
$allSections = $services->getAllSectionsWithServices();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['save_about'])) {
        if ($services->saveAboutContent($_POST['title'], $_POST['description'])) {
            $_SESSION['message'] = 'About section updated successfully!';
        } else {
            $_SESSION['error'] = 'Failed to update about section.';
        }
    } elseif (isset($_POST['save_section'])) {
        $sectionId = $_POST['section_id'];
        $sectionTitle = $_POST['section_title'];
        $sectionDesc = $_POST['section_description'];

        if ($services->saveSection($sectionId, $sectionTitle, $sectionDesc)) {
            for ($i = 0; $i < 3; $i++) {
                if (!empty($_POST['service_title'][$i])) {
                    $serviceData = [
                        'title' => $_POST['service_title'][$i],
                        'description' => $_POST['service_description'][$i],
                        'image' => $_FILES['service_image']['name'][$i] ?? null,
                        'section_title' => $sectionTitle
                    ];

                    // Handle file upload
                    if (!empty($_FILES['service_image']['tmp_name'][$i])) {
                        $targetDir = "../assets/images/services/";
                        $fileName = basename($_FILES["service_image"]["name"][$i]);
                        $targetFile = $targetDir . $fileName;
                        move_uploaded_file($_FILES["service_image"]["tmp_name"][$i], $targetFile);
                        $serviceData['image'] = $fileName;
                    }

                    $services->saveService($sectionId, $serviceData, $i);
                }
            }
            $_SESSION['message'] = 'Section updated successfully!';
        } else {
            $_SESSION['error'] = 'Failed to update section.';
        }
    } elseif (isset($_POST['delete_section'])) {
        if ($services->deleteSection($_POST['section_id'])) {
            $_SESSION['message'] = 'Section deleted successfully!';
        } else {
            $_SESSION['error'] = 'Failed to delete section.';
        }
    }

    header('location: userServices.php');
    exit();
}

?>


<!DOCTYPE html>
<html lang="en">
<?php
$title = 'Admin | User Services';
include './includes/admin_head.php';
function getCurrentPage()
{
    return basename($_SERVER['PHP_SELF']);
}
?>

<body>
    <?php
    require_once('./includes/admin_header.php');
    require_once('./includes/admin_sidepanel.php');
    ?>

    <section id="userPage" class="page-container">
        <?php
        $services = 'active';
        $aServices = 'page';
        $cServices = 'text-dark';
        include './includes/adminUserPage_nav.php';
        ?>

        <h1 class="text-start mb-3">User Services</h1>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['message'];
                                                unset($_SESSION['message']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['error'];
                                            unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <!-- About Section -->
        <button class="btn btn-link text-start ps-0 d-flex align-items-center" type="button" data-bs-toggle="collapse" data-bs-target="#aboutSection" aria-expanded="true" aria-controls="aboutSection">
            <h5 class="card-title mb-0">About Section</h5>
            <i class='bx bxs-chevron-down ms-2 chevron-icon' data-target="aboutSection"></i>
        </button>
        <hr class="mt-1 mb-2">
        <div class="collapse" id="aboutSection">
            <div class="card mb-4">
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="save_about" value="1">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" name="title"
                                value="<?php echo htmlspecialchars($aboutContent['section_title'] ?? 'Telecommunication Health Services'); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="4" required><?php
                                                                                                echo htmlspecialchars($aboutContent['section_description'] ?? 'Welcome to DocConnect\'s Telecommunication Health Services! Our goal is to provide you with seamless access to quality healthcare and wellness resources from the comfort of your home or office.');
                                                                                                ?></textarea>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary text-light">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Add New Section -->
        <button class="btn btn-link text-start ps-0 d-flex align-items-center" type="button" data-bs-toggle="collapse" data-bs-target="#newSection" aria-expanded="false" aria-controls="newSection">
            <h5 class="card-title mb-0">Add New Section</h5>
            <i class='bx bxs-chevron-down ms-2 chevron-icon' data-target="newSection"></i>
        </button>
        <hr class="mt-1 mb-2">
        <div class="collapse show" id="newSection">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Add New Section</h5>
                </div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="save_section" value="1">
                        <input type="hidden" name="section_id" value="<?php echo count($allSections) + 1; ?>">

                        <div class="mb-3">
                            <label class="form-label">Section Title</label>
                            <input type="text" class="form-control" name="section_title" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Section Description</label>
                            <textarea class="form-control" name="section_description" rows="2" required></textarea>
                        </div>

                        <h6 class="mt-4 mb-3">Services (Max 3)</h6>

                        <div class="row">
                            <?php for ($i = 0; $i < 3; $i++): ?>
                                <div class="col-md-4 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label class="form-label">Service Image</label>
                                                <input type="file" class="form-control" name="service_image[]">
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Service Title</label>
                                                <input type="text" class="form-control" name="service_title[]">
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Service Description</label>
                                                <textarea class="form-control" name="service_description[]" rows="3"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endfor; ?>
                        </div>

                        <div class="d-flex justify-content-end mt-3">
                            <button type="submit" class="btn btn-green text-light">Add New Section</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Existing Sections -->
        <?php foreach ($allSections as $index => $section): ?>
            <?php $sectionId = $index + 1; ?>
            <button class="btn btn-link text-start ps-0 d-flex align-items-center" type="button" data-bs-toggle="collapse" data-bs-target="#section<?php echo $sectionId; ?>" aria-expanded="true" aria-controls="section<?php echo $sectionId; ?>">
                <h5 class="card-title mb-0">Section <?php echo $sectionId; ?></h5>
                <i class='bx bxs-chevron-down ms-2 chevron-icon' data-target="section<?php echo $sectionId; ?>"></i>
            </button>
            <hr class="mt-1 mb-2">
            <div class="collapse" id="section<?php echo $sectionId; ?>">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Section <?php echo $sectionId; ?></h5>
                        <form method="POST" class="d-inline">
                            <input type="hidden" name="section_id" value="<?php echo $sectionId; ?>">
                            <input type="hidden" name="delete_section" value="1">
                            <button type="submit" class="btn btn-danger btn-sm text-light"
                                onclick="return confirm('Are you sure you want to delete this section and all its services?')">
                                Delete Section
                            </button>
                        </form>
                    </div>
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="save_section" value="1">
                            <input type="hidden" name="section_id" value="<?php echo $sectionId; ?>">

                            <div class="mb-3">
                                <label class="form-label">Section Title</label>
                                <input type="text" class="form-control" name="section_title"
                                    value="<?php echo htmlspecialchars($section['section_title']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Section Description</label>
                                <textarea class="form-control" name="section_description" rows="2" required><?php
                                                                                                            echo htmlspecialchars($section['section_description']);
                                                                                                            ?></textarea>
                            </div>

                            <h6 class="mt-4 mb-3">Services (Max 3)</h6>

                            <div class="row">
                                <?php for ($i = 0; $i < 3; $i++): ?>
                                    <div class="col-md-4 mb-3">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Service Image</label>
                                                    <input type="file" class="form-control" name="service_image[]">
                                                    <?php if (isset($section['services'][$i]['image'])): ?>
                                                        <small class="text-muted">Current: <?php echo htmlspecialchars($section['services'][$i]['image']); ?></small>
                                                    <?php endif; ?>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Service Title</label>
                                                    <input type="text" class="form-control" name="service_title[]"
                                                        value="<?php echo isset($section['services'][$i]) ? htmlspecialchars($section['services'][$i]['title']) : ''; ?>">
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Service Description</label>
                                                    <textarea class="form-control" name="service_description[]" rows="3"><?php
                                                                                                                            echo isset($section['services'][$i]) ? htmlspecialchars($section['services'][$i]['description']) : '';
                                                                                                                            ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endfor; ?>
                            </div>

                            <div class="d-flex justify-content-end mt-3">
                                <button type="submit" class="btn btn-primary text-light">Save Section</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </section>

    <?php include './includes/admin_footer.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(button => {
                const targetId = button.getAttribute('data-bs-target');
                const targetElement = document.querySelector(targetId);
                const chevronIcon = button.querySelector('.chevron-icon');

                if (targetElement.classList.contains('show')) {
                    chevronIcon.classList.add('rotate-180');
                }

                button.addEventListener('click', function() {
                    chevronIcon.classList.toggle('rotate-180');
                });
            });
        });
    </script>

    <style>
        .chevron-icon {
            transition: transform 0.3s ease;
        }

        .rotate-180 {
            transform: rotate(180deg);
        }
    </style>
</body>

</html>