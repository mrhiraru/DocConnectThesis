<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
    header('location: ../user/verification.php');
    exit();
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 0) {
    header('location: ../index.php');
    exit();
}

require_once('../tools/functions.php');
require_once('../classes/account.class.php');
require_once('../classes/doctorsPage.class.php');
require_once('../classes/database.php');

try {
    $doctor = new Account();
    $doctorsPage = new DoctorsPage();

    $introContent = $doctorsPage->getSectionContent('intro');
    $specializationsContent = $doctorsPage->getSectionContent('specializations');
    $telehealthContent = $doctorsPage->getSectionContent('telehealth');
    $communityContent = $doctorsPage->getSectionContent('community');
    $accessibilityContent = $doctorsPage->getSectionContent('accessibility');

    $allDoctors = $doctor->show_doc();
    $today = date('Ymd');
    mt_srand($today);
    shuffle($allDoctors);
    $doctorArray = array_slice($allDoctors, 0, 5);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        try {
            if (isset($_POST['save_intro'])) {
                if ($doctorsPage->updateIntro($_POST['intro_title'], $_POST['intro_content'])) {
                    $_SESSION['message'] = 'Introduction section updated successfully!';
                } else {
                    $_SESSION['error'] = 'Failed to update introduction section.';
                }
            } elseif (isset($_POST['save_specializations'])) {
                $success = $doctorsPage->updateSpecializations(
                    $_POST['specializations_title'],
                    $_POST['specializations_subtitle'],
                    $_POST['spec_title'][0],
                    $_POST['spec_content'][0],
                    $_POST['spec_title'][1],
                    $_POST['spec_content'][1],
                    $_POST['spec_title'][2],
                    $_POST['spec_content'][2]
                );

                if ($success) {
                    $_SESSION['message'] = 'Specializations section updated successfully!';
                } else {
                    $_SESSION['error'] = 'Failed to update specializations section.';
                }
            } elseif (isset($_POST['save_telehealth'])) {
                $imagePath = $telehealthContent['image_path'];

                // Handle image upload if new image was provided
                if (!empty($_FILES['telehealth_image']['name'])) {
                    $imagePath = $doctorsPage->uploadImage($_FILES['telehealth_image']);
                }

                $quote = $_POST['telehealth_quote'] ?? '';
                $quoteAuthor = $_POST['telehealth_quote_author'] ?? '';

                if ($doctorsPage->updateTelehealth(
                    $_POST['telehealth_title'],
                    $_POST['telehealth_content'],
                    $imagePath,
                    $quote,
                    $quoteAuthor
                )) {
                    $_SESSION['message'] = 'Telehealth section updated successfully!';
                } else {
                    $_SESSION['error'] = 'Failed to update telehealth section.';
                }
            } elseif (isset($_POST['save_community'])) {
                $imagePath = $communityContent['image_path'];

                // Handle image upload if new image was provided
                if (!empty($_FILES['community_image']['name'])) {
                    $imagePath = $doctorsPage->uploadImage($_FILES['community_image']);
                }

                if ($doctorsPage->updateCommunity(
                    $_POST['community_title'],
                    $_POST['community_content'],
                    $imagePath
                )) {
                    $_SESSION['message'] = 'Community impact section updated successfully!';
                } else {
                    $_SESSION['error'] = 'Failed to update community impact section.';
                }
            } elseif (isset($_POST['save_accessibility'])) {
                $imagePath = $accessibilityContent['image_path'];

                // Handle image upload if new image was provided
                if (!empty($_FILES['accessibility_image']['name'])) {
                    $imagePath = $doctorsPage->uploadImage($_FILES['accessibility_image']);
                }

                if ($doctorsPage->updateAccessibility(
                    $_POST['accessibility_title'],
                    $_POST['accessibility_content'],
                    $imagePath
                )) {
                    $_SESSION['message'] = 'Accessibility section updated successfully!';
                } else {
                    $_SESSION['error'] = 'Failed to update accessibility section.';
                }
            }

            header('location: userDoctors.php');
            exit();
        } catch (Exception $e) {
            $_SESSION['error'] = 'An error occurred: ' . $e->getMessage();
            header('location: userDoctors.php');
            exit();
        }
    }
} catch (Exception $e) {
    $_SESSION['error'] = 'An error occurred: ' . $e->getMessage();
    header('location: userDoctors.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<?php
$title = 'Admin | User Doctors';
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
        $doctors = 'active';
        $aDoctors = 'page';
        $cDoctors = 'text-dark';
        include './includes/adminUserPage_nav.php';
        ?>

        <h1 class="text-start mb-3">User Doctors</h1>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['message'];
                                                unset($_SESSION['message']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['error'];
                                            unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <!-- Introduction Section -->
        <button class="btn btn-link text-start ps-0" type="button" data-bs-toggle="collapse" data-bs-target="#introSection" aria-expanded="true" aria-controls="introSection">
            <div class="d-flex flex-row align-items-center">
                <h5 class="card-title">Introduction Section</h5>
                <i id="chevronIconIntroduction" class='bx bxs-chevron-up ms-2'></i>
            </div>
        </button>
        <hr class="mt-1 mb-2">
        <div class="collapse show" id="introSection">
            <div class="card mb-4">
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="save_intro" value="1">
                        <div class="mb-3">
                            <label for="introTitle" class="form-label">Title</label>
                            <input type="text" class="form-control" name="intro_title"
                                value="<?php echo htmlspecialchars($introContent['title']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="introContent" class="form-label">Content</label>
                            <textarea class="form-control" name="intro_content" rows="5" required><?php
                                                                                                    echo htmlspecialchars($introContent['content']);
                                                                                                    ?></textarea>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary text-light">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Specializations Section -->
        <button class="btn btn-link text-start ps-0" type="button" data-bs-toggle="collapse" data-bs-target="#specializationsSection" aria-expanded="false" aria-controls="specializationsSection">
            <div class="d-flex flex-row align-items-center">
                <h5 class="card-title">Specializations Section</h5>
                <i id="chevronIconSpecializations" class='bx bxs-chevron-down ms-2'></i>
            </div>
        </button>
        <hr class="mt-1 mb-2">
        <div class="collapse" id="specializationsSection">
            <div class="card mb-4">
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="save_specializations" value="1">
                        <div class="mb-3">
                            <label for="specializationsTitle" class="form-label">Section Title</label>
                            <input type="text" class="form-control" name="specializations_title"
                                value="<?php echo htmlspecialchars($specializationsContent['title']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="specializationsSubtitle" class="form-label">Subtitle</label>
                            <input type="text" class="form-control" name="specializations_subtitle"
                                value="<?php echo htmlspecialchars($specializationsContent['content']); ?>" required>
                        </div>

                        <h6 class="mt-4 mb-3">Specialization Cards</h6>

                        <div class="row">
                            <?php for ($i = 0; $i < 3; $i++): ?>
                                <div class="col-md-4 mb-4">
                                    <div class="card h-100">
                                        <div class="card-header bg-primary text-white">
                                            Specialization <?php echo $i + 1; ?>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label class="form-label">Title</label>
                                                <input type="text" class="form-control" name="spec_title[]"
                                                    value="<?php echo htmlspecialchars($specializationsContent['spec' . ($i + 1) . '_title']); ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Content</label>
                                                <textarea class="form-control" name="spec_content[]" rows="3" required><?php
                                                                                                                        echo htmlspecialchars($specializationsContent['spec' . ($i + 1) . '_content']);
                                                                                                                        ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endfor; ?>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary text-light">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Telehealth Advantage Section -->
        <button class="btn btn-link text-start ps-0" type="button" data-bs-toggle="collapse" data-bs-target="#telehealthSection" aria-expanded="false" aria-controls="telehealthSection">
            <div class="d-flex flex-row align-items-center">
                <h5 class="card-title">Telehealth Advantage Section</h5>
                <i id="chevronIconTelehealth" class='bx bxs-chevron-down ms-2'></i>
            </div>
        </button>
        <hr class="mt-1 mb-2">
        <div class="collapse" id="telehealthSection">
            <div class="card mb-4">
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="save_telehealth" value="1">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Section Title</label>
                                    <input type="text" class="form-control" name="telehealth_title"
                                        value="<?php echo htmlspecialchars($telehealthContent['title']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Content</label>
                                    <textarea class="form-control" name="telehealth_content" rows="3" required><?php
                                                                                                                echo htmlspecialchars($telehealthContent['content']);
                                                                                                                ?></textarea>
                                </div>
                                <!-- <div class="mb-3">
                                    <label class="form-label">Testimonial Quote</label>
                                    <textarea class="form-control" name="telehealth_quote" rows="2"><?php
                                                                                                    echo htmlspecialchars($telehealthContent['quote']);
                                                                                                    ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Testimonial Author</label>
                                    <input type="text" class="form-control" name="telehealth_quote_author"
                                        value="<?php echo htmlspecialchars($telehealthContent['quote_author']); ?>">
                                </div> -->
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Image</label>
                                    <input type="file" class="form-control" name="telehealth_image">
                                    <small class="text-muted">Current: <?php echo htmlspecialchars($telehealthContent['image_path']); ?></small>
                                </div>
                                <div class="text-center">
                                    <img src="../assets/images/<?php echo htmlspecialchars($telehealthContent['image_path']); ?>"
                                        alt="Current Telehealth Image" class="img-fluid rounded shadow mt-2" style="max-height: 200px;">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary text-light">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Community Impact Section -->
        <button class="btn btn-link text-start ps-0" type="button" data-bs-toggle="collapse" data-bs-target="#communitySection" aria-expanded="false" aria-controls="communitySection">
            <div class="d-flex flex-row align-items-center">
                <h5 class="card-title">Community Impact Section</h5>
                <i id="chevronIconCommunity" class='bx bxs-chevron-down ms-2'></i>
            </div>
        </button>
        <hr class="mt-1 mb-2">
        <div class="collapse" id="communitySection">
            <div class="card mb-4">
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="save_community" value="1">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Section Title</label>
                                    <input type="text" class="form-control" name="community_title"
                                        value="<?php echo htmlspecialchars($communityContent['title']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Content</label>
                                    <textarea class="form-control" name="community_content" rows="5" required><?php
                                                                                                                echo htmlspecialchars($communityContent['content']);
                                                                                                                ?></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Image</label>
                                    <input type="file" class="form-control" name="community_image">
                                    <small class="text-muted">Current: <?php echo htmlspecialchars($communityContent['image_path']); ?></small>
                                </div>
                                <div class="text-center">
                                    <img src="../assets/images/<?php echo htmlspecialchars($communityContent['image_path']); ?>"
                                        alt="Current Community Impact Image" class="img-fluid rounded shadow mt-2" style="max-height: 200px;">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary text-light">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Accessibility Section -->
        <button class="btn btn-link text-start ps-0" type="button" data-bs-toggle="collapse" data-bs-target="#accessibilitySection" aria-expanded="false" aria-controls="accessibilitySection">
            <div class="d-flex flex-row align-items-center">
                <h5 class="card-title">Accessibility Section</h5>
                <i id="chevronIconAccessibility" class='bx bxs-chevron-down ms-2'></i>
            </div>
        </button>
        <hr class="mt-1 mb-2">
        <div class="collapse" id="accessibilitySection">
            <div class="card mb-4">
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="save_accessibility" value="1">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Section Title</label>
                                    <input type="text" class="form-control" name="accessibility_title"
                                        value="<?php echo htmlspecialchars($accessibilityContent['title']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Content</label>
                                    <textarea class="form-control" name="accessibility_content" rows="5" required><?php
                                                                                                                    echo htmlspecialchars($accessibilityContent['content']);
                                                                                                                    ?></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Image</label>
                                    <input type="file" class="form-control" name="accessibility_image">
                                    <small class="text-muted">Current: <?php echo htmlspecialchars($accessibilityContent['image_path']); ?></small>
                                </div>
                                <div class="text-center">
                                    <img src="../assets/images/<?php echo htmlspecialchars($accessibilityContent['image_path']); ?>"
                                        alt="Current Accessibility Image" class="img-fluid rounded shadow mt-2" style="max-height: 200px;">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary text-light">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script>
        const mainButton = document.querySelector('[data-bs-target="#main"]');
        const chevronIconMain = document.getElementById('chevronIconMain');

        mainButton.addEventListener('click', function() {
            chevronIconMain.classList.toggle('rotate-180');
        });
    </script>

</body>

</html>