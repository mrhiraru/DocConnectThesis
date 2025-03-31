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

$doctor = new Account();
$allDoctors = $doctor->show_doc();
$today = date('Ymd');
mt_srand($today);
shuffle($allDoctors);
$doctorArray = array_slice($allDoctors, 0, 5);

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
            <h5 class="card-title">Introduction Section</h5>
        </button>
        <hr class="mt-1 mb-2">
        <div class="collapse show" id="introSection">
            <div class="card mb-4">
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="save_intro" value="1">
                        <div class="mb-3">
                            <label for="introTitle" class="form-label">Title</label>
                            <input type="text" class="form-control" name="intro_title" value="Our Doctors" required>
                        </div>
                        <div class="mb-3">
                            <label for="introContent" class="form-label">Content</label>
                            <textarea class="form-control" name="intro_content" rows="5" required>At Western Mindanao State University, our telehealth platform combines cutting-edge technology with compassionate care. Our team of dedicated doctors is here to serve not just our university community but also the broader Zamboanga Peninsula, fostering a culture of wellness and health awareness. Whether you need routine care, specialized advice, or preventive consultation, we are committed to delivering accessible and high-quality healthcare tailored to your needs.</textarea>
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
            <h5 class="card-title">Specializations Section</h5>
        </button>
        <hr class="mt-1 mb-2">
        <div class="collapse" id="specializationsSection">
            <div class="card mb-4">
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="save_specializations" value="1">
                        <div class="mb-3">
                            <label for="specializationsTitle" class="form-label">Section Title</label>
                            <input type="text" class="form-control" name="specializations_title" value="Our Specializations" required>
                        </div>
                        <div class="mb-3">
                            <label for="specializationsSubtitle" class="form-label">Subtitle</label>
                            <input type="text" class="form-control" name="specializations_subtitle" value="Our team of expert doctors provides care in the following areas:" required>
                        </div>

                        <h6 class="mt-4 mb-3">Specialization Cards</h6>

                        <div class="row">
                            <?php
                            $specTitles = ["General Medicine", "Mental Health", "Dentistry"];
                            $specContents = [
                                "We provide comprehensive primary care to address a wide range of health concerns, ensuring the overall well-being of our diverse university community.",
                                "We support mental wellness to help students and staff manage stress, thrive academically, and foster a healthier, more supportive campus environment.",
                                "We promote good oral health through preventive and restorative care, helping everyone maintain confident smiles and overall well-being."
                            ];
                            ?>

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
                                                    value="<?php echo htmlspecialchars($specTitles[$i]); ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Content</label>
                                                <textarea class="form-control" name="spec_content[]" rows="3" required><?php
                                                                                                                        echo htmlspecialchars($specContents[$i]);
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
            <h5 class="card-title">Telehealth Advantage Section</h5>
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
                                        value="Our Telehealth Advantage" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Content</label>
                                    <textarea class="form-control" name="telehealth_content" rows="3" required>Leveraging cutting-edge technology, we offer secure and accessible teleconsultations that bring quality care to the comfort of your home.</textarea>
                                </div>
                                <!-- <div class="mb-3">
                                    <label class="form-label">Testimonial Quote</label>
                                    <textarea class="form-control" name="telehealth_quote" rows="2" required>"The doctors were so attentive and helpful. Telehealth made it easy to consult from home."</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Testimonial Author</label>
                                    <input type="text" class="form-control" name="telehealth_quote_author"
                                        value="Satisfied User" required>
                                </div> -->
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Image</label>
                                    <input type="file" class="form-control" name="telehealth_image">
                                    <small class="text-muted">Current: doctors_telehealth.png</small>
                                </div>
                                <div class="text-center">
                                    <img src="../assets/images/doctors_telehealth.png" alt="Current Telehealth Image"
                                        class="img-fluid rounded shadow mt-2" style="max-height: 200px;">
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
            <h5 class="card-title">Community Impact Section</h5>
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
                                        value="Making an Impact" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Content</label>
                                    <textarea class="form-control" name="community_content" rows="5" required>Our doctors are committed to giving back through outreach programs, health education seminars, and volunteer initiatives that promote wellness across Zamboanga Peninsula.</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Image</label>
                                    <input type="file" class="form-control" name="community_image">
                                    <small class="text-muted">Current: doctors_community-impact.png</small>
                                </div>
                                <div class="text-center">
                                    <img src="../assets/images/doctors_community-impact.png" alt="Current Community Impact Image"
                                        class="img-fluid rounded shadow mt-2" style="max-height: 200px;">
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
            <h5 class="card-title">Accessibility Section</h5>
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
                                        value="Accessible to All" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Content</label>
                                    <textarea class="form-control" name="accessibility_content" rows="5" required>We strive to ensure that everyone can access our services, regardless of ability. Alternative formats and tools are available for users with disabilities.</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Image</label>
                                    <input type="file" class="form-control" name="accessibility_image">
                                    <small class="text-muted">Current: doctors_accessibility.png</small>
                                </div>
                                <div class="text-center">
                                    <img src="../assets/images/doctors_accessibility.png" alt="Current Accessibility Image"
                                        class="img-fluid rounded shadow mt-2" style="max-height: 200px;">
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

    <?php include './includes/admin_footer.php'; ?>
</body>

</html>