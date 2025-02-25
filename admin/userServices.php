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
        $services = 'active';
        $aServices = 'page';
        $cServices = 'text-dark';

        include './includes/adminUserPage_nav.php';
        ?>

        <h1 class="text-start mb-3">User Services</h1>
        <h6 class="text-start mb-4 text-muted">Icon Class: <a href="https://boxicons.com/" target="_blank">Boxicons.com</a></h6>

        <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#aboutSection" aria-expanded="false" aria-controls="aboutSection">
            <h5 class="card-title">About Section</h5>
        </button>
        <hr class="mt-1 mb-2">
        <div class="collapse" id="aboutSection">
            <div class="card mb-3 w-100">
                <div class="card-body">
                    <form class="p-3 pb-md-4 mx-auto text-center">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" value="Telecommunication Health Services">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" rows="4">Welcome to DocConnect's Telecommunication Health Services! Our goal is to provide you with seamless access to quality healthcare and wellness resources from the comfort of your home or office. Our comprehensive suite of telecommunication health services ensures that you receive the care you need when you need it, without the hassle of traveling to a clinic. Here's what we offer:</textarea>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary text-light">Save</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#secondSection" aria-expanded="false" aria-controls="secondSection">
            <h5 class="card-title">Service One</h5>
        </button>
        <hr class="mt-1 mb-2">
        <div class="collapse" id="secondSection">
            <div class="card w-100">
                <div class="card-body">
                    <form class="mb-5">
                        <h2 class="text-primary">Service one</h2>

                        <!-- Section Title -->
                        <div class="mb-3">
                            <label for="sectionTitle" class="form-label">Section Title</label>
                            <input type="text" class="form-control" id="sectionTitle" value="Virtual Consultations">
                        </div>

                        <!-- Section Description -->
                        <div class="mb-3">
                            <label for="sectionDescription" class="form-label">Section Description</label>
                            <textarea class="form-control" id="sectionDescription" rows="3">Skip the waiting room—consult with healthcare professionals online!</textarea>
                        </div>

                        <div class="row">
                            <!-- Card 1 -->
                            <div class="col-md-4 mb-4">
                                <div class="card service-card shadow-sm p-3">
                                    <label class="form-label">Upload Image</label>
                                    <input type="file" class="form-control mb-2">
                                    <label class="form-label">Title</label>
                                    <input type="text" class="form-control mb-2" value="Expert Medical Advice">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" rows="2">Receive personalized guidance and treatment plans from licensed healthcare providers.</textarea>
                                </div>
                            </div>

                            <!-- Card 2 -->
                            <div class="col-md-4 mb-4">
                                <div class="card service-card shadow-sm p-3">
                                    <label class="form-label">Upload Image</label>
                                    <input type="file" class="form-control mb-2">
                                    <label class="form-label">Title</label>
                                    <input type="text" class="form-control mb-2" value="Convenient Appointments">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" rows="2">Book consultations at your preferred time and attend from any device.</textarea>
                                </div>
                            </div>

                            <!-- Card 3 -->
                            <div class="col-md-4 mb-4">
                                <div class="card service-card shadow-sm p-3">
                                    <label class="form-label">Upload Image</label>
                                    <input type="file" class="form-control mb-2">
                                    <label class="form-label">Title</label>
                                    <input type="text" class="form-control mb-2" value="Follow-Up Care">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" rows="2">Get continuous support to ensure your health management is effective.</textarea>
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

        <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#servicetwo" aria-expanded="false" aria-controls="servicetwo">
            <h5 class="card-title">Service Two</h5>
        </button>
        <hr class="mt-1 mb-2">
        <div class="collapse" id="servicetwo">
            <div class="card w-100">
                <div class="card-body">
                    <form class="mb-5">
                        <h2 class="text-primary">Service Two</h2>

                        <!-- Section Title -->
                        <div class="mb-3">
                            <label for="sectionTitle" class="form-label">Section Title</label>
                            <input type="text" class="form-control" id="sectionTitle" value="Virtual Consultations">
                        </div>

                        <!-- Section Description -->
                        <div class="mb-3">
                            <label for="sectionDescription" class="form-label">Section Description</label>
                            <textarea class="form-control" id="sectionDescription" rows="3">Skip the waiting room—consult with healthcare professionals online!</textarea>
                        </div>

                        <div class="row">
                            <!-- Card 1 -->
                            <div class="col-md-4 mb-4">
                                <div class="card service-card shadow-sm p-3">
                                    <label class="form-label">Upload Image</label>
                                    <input type="file" class="form-control mb-2">
                                    <label class="form-label">Title</label>
                                    <input type="text" class="form-control mb-2" value="Expert Medical Advice">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" rows="2">Receive personalized guidance and treatment plans from licensed healthcare providers.</textarea>
                                </div>
                            </div>

                            <!-- Card 2 -->
                            <div class="col-md-4 mb-4">
                                <div class="card service-card shadow-sm p-3">
                                    <label class="form-label">Upload Image</label>
                                    <input type="file" class="form-control mb-2">
                                    <label class="form-label">Title</label>
                                    <input type="text" class="form-control mb-2" value="Convenient Appointments">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" rows="2">Book consultations at your preferred time and attend from any device.</textarea>
                                </div>
                            </div>

                            <!-- Card 3 -->
                            <div class="col-md-4 mb-4">
                                <div class="card service-card shadow-sm p-3">
                                    <label class="form-label">Upload Image</label>
                                    <input type="file" class="form-control mb-2">
                                    <label class="form-label">Title</label>
                                    <input type="text" class="form-control mb-2" value="Follow-Up Care">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" rows="2">Get continuous support to ensure your health management is effective.</textarea>
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

        <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#servicethree" aria-expanded="false" aria-controls="servicethree">
            <h5 class="card-title">Service Three</h5>
        </button>
        <hr class="mt-1 mb-2">
        <div class="collapse" id="servicethree">
            <div class="card w-100">
                <div class="card-body">
                    <form class="mb-5">
                        <h2 class="text-primary">Service Three</h2>

                        <!-- Section Title -->
                        <div class="mb-3">
                            <label for="sectionTitle" class="form-label">Section Title</label>
                            <input type="text" class="form-control" id="sectionTitle" value="Virtual Consultations">
                        </div>

                        <!-- Section Description -->
                        <div class="mb-3">
                            <label for="sectionDescription" class="form-label">Section Description</label>
                            <textarea class="form-control" id="sectionDescription" rows="3">Skip the waiting room—consult with healthcare professionals online!</textarea>
                        </div>

                        <div class="row">
                            <!-- Card 1 -->
                            <div class="col-md-4 mb-4">
                                <div class="card service-card shadow-sm p-3">
                                    <label class="form-label">Upload Image</label>
                                    <input type="file" class="form-control mb-2">
                                    <label class="form-label">Title</label>
                                    <input type="text" class="form-control mb-2" value="Expert Medical Advice">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" rows="2">Receive personalized guidance and treatment plans from licensed healthcare providers.</textarea>
                                </div>
                            </div>

                            <!-- Card 2 -->
                            <div class="col-md-4 mb-4">
                                <div class="card service-card shadow-sm p-3">
                                    <label class="form-label">Upload Image</label>
                                    <input type="file" class="form-control mb-2">
                                    <label class="form-label">Title</label>
                                    <input type="text" class="form-control mb-2" value="Convenient Appointments">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" rows="2">Book consultations at your preferred time and attend from any device.</textarea>
                                </div>
                            </div>

                            <!-- Card 3 -->
                            <div class="col-md-4 mb-4">
                                <div class="card service-card shadow-sm p-3">
                                    <label class="form-label">Upload Image</label>
                                    <input type="file" class="form-control mb-2">
                                    <label class="form-label">Title</label>
                                    <input type="text" class="form-control mb-2" value="Follow-Up Care">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" rows="2">Get continuous support to ensure your health management is effective.</textarea>
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

        <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#servicefour" aria-expanded="false" aria-controls="servicefour">
            <h5 class="card-title">Service Four</h5>
        </button>
        <hr class="mt-1 mb-2">
        <div class="collapse" id="servicefour">
            <div class="card w-100">
                <div class="card-body">
                    <form class="mb-5">
                        <h2 class="text-primary">Service four</h2>

                        <!-- Section Title -->
                        <div class="mb-3">
                            <label for="sectionTitle" class="form-label">Section Title</label>
                            <input type="text" class="form-control" id="sectionTitle" value="Virtual Consultations">
                        </div>

                        <!-- Section Description -->
                        <div class="mb-3">
                            <label for="sectionDescription" class="form-label">Section Description</label>
                            <textarea class="form-control" id="sectionDescription" rows="3">Skip the waiting room—consult with healthcare professionals online!</textarea>
                        </div>

                        <div class="row">
                            <!-- Card 1 -->
                            <div class="col-md-4 mb-4">
                                <div class="card service-card shadow-sm p-3">
                                    <label class="form-label">Upload Image</label>
                                    <input type="file" class="form-control mb-2">
                                    <label class="form-label">Title</label>
                                    <input type="text" class="form-control mb-2" value="Expert Medical Advice">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" rows="2">Receive personalized guidance and treatment plans from licensed healthcare providers.</textarea>
                                </div>
                            </div>

                            <!-- Card 2 -->
                            <div class="col-md-4 mb-4">
                                <div class="card service-card shadow-sm p-3">
                                    <label class="form-label">Upload Image</label>
                                    <input type="file" class="form-control mb-2">
                                    <label class="form-label">Title</label>
                                    <input type="text" class="form-control mb-2" value="Convenient Appointments">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" rows="2">Book consultations at your preferred time and attend from any device.</textarea>
                                </div>
                            </div>

                            <!-- Card 3 -->
                            <div class="col-md-4 mb-4">
                                <div class="card service-card shadow-sm p-3">
                                    <label class="form-label">Upload Image</label>
                                    <input type="file" class="form-control mb-2">
                                    <label class="form-label">Title</label>
                                    <input type="text" class="form-control mb-2" value="Follow-Up Care">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" rows="2">Get continuous support to ensure your health management is effective.</textarea>
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