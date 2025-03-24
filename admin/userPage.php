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
                    <form class="p-2 bg-white rounded">
                        <div class="mb-3">
                            <label for="heading" class="form-label">Heading</label>
                            <input type="text" class="form-control" id="heading" value="Connectivity that Heals">
                        </div>
                        <div class="mb-3">
                            <label for="subheading" class="form-label">Subheading</label>
                            <textarea class="form-control" id="subheading" rows="2">Revolutionizing Healthcare Through Telecommunications</textarea>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary text-light">Save</button>
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
                    <form>
                        <div class="mb-3">
                            <label for="sectionTitle" class="form-label">Section Title</label>
                            <input type="text" class="form-control" id="sectionTitle" value="Experience the Benefits of Our Telecommunication Health services">
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

        <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#ourServices" aria-expanded="false" aria-controls="ourServices">
            <h5 class="card-title">Our Services</h5>
        </button>
        <hr class="mt-1 mb-2">
        <div class="collapse" id="ourServices">
            <div class="card w-100">
                <div class="card-body">
                    <form>
                        <div class="mb-3">
                            <label for="sectionTitle" class="form-label">Section Title</label>
                            <input type="text" class="form-control" id="sectionTitle" value="Telecommunication Health Services for Your Convenience">
                        </div>

                        <div class="row row-cols-1 row-cols-md-3 g-4 mb-3">
                            <div class="col">
                                <div class="card h-100 p-2">
                                    <label for="image1" class="form-label">Upload Image 1</label>
                                    <input type="file" class="form-control" id="image1" onchange="previewImage(event, 'preview1')">
                                    <img id="preview1" src="../assets/images/services/expert_medical_advice.png" class="card-img-top" style="height: 250px; object-fit: cover;">
                                    <div class="card-body">
                                        <label for="title1" class="form-label">Title</label>
                                        <input type="text" class="form-control" id="title1" value="Virtual Consultations">
                                        <label for="desc1" class="form-label">Description</label>
                                        <textarea class="form-control" id="desc1" rows="3">Skip the waiting room, consult with healthcare professionals online!</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col">
                                <div class="card h-100 p-2">
                                    <label for="image2" class="form-label">Upload Image 2</label>
                                    <input type="file" class="form-control" id="image2" onchange="previewImage(event, 'preview2')">
                                    <img id="preview2" src="../assets/images/services/health_metrics_tracking.png" class="card-img-top" style="height: 250px; object-fit: cover;">
                                    <div class="card-body">
                                        <label for="title2" class="form-label">Title</label>
                                        <input type="text" class="form-control" id="title2" value="Remote Monitoring">
                                        <label for="desc2" class="form-label">Description</label>
                                        <textarea class="form-control" id="desc2" rows="3">Stay in control of your health with cutting-edge tools.</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col">
                                <div class="card h-100 p-2">
                                    <label for="image3" class="form-label">Upload Image 3</label>
                                    <input type="file" class="form-control" id="image3" onchange="previewImage(event, 'preview3')">
                                    <img id="preview3" src="../assets/images/services/counseling_services.png" class="card-img-top" style="height: 250px; object-fit: cover;">
                                    <div class="card-body">
                                        <label for="title3" class="form-label">Title</label>
                                        <input type="text" class="form-control" id="title3" value="Mental Health Support">
                                        <label for="desc3" class="form-label">Description</label>
                                        <textarea class="form-control" id="desc3" rows="3">Access mental health resources anytime, anywhere.</textarea>
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

        <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#information" aria-expanded="false" aria-controls="information">
            <h5 class="card-title">Information</h5>
        </button>
        <hr class="mt-1 mb-2">
        <div class="collapse" id="information">
            <div class="card w-100">
                <div class="card-body">
                    <form>
                        <h2 class="mb-3">Edit Telemedicine Content</h2>

                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" class="form-control" value="Telemedicine">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Subtitle</label>
                            <textarea class="form-control" rows="2">Bridging the gap between distance and healthcare accessibility.</textarea>
                        </div>

                        <h4 class="mt-4">Services</h4>

                        <div class="card p-3 mb-3">
                            <div class="mb-3">
                                <label class="form-label">Icon class</label>
                                <input type="text" class="form-control" value="bxs-phone-call">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" class="form-control" value="Doctor on Call">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" rows="3">Get instant access to licensed doctors anytime, anywhere. Consult via phone or video calls for expert medical advice and treatment recommendations.</textarea>
                            </div>
                        </div>

                        <div class="card p-3 mb-3">
                            <div class="mb-3">
                                <label class="form-label">Icon class</label>
                                <input type="text" class="form-control" value="bxs-book-reader">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" class="form-control" value="Health Education">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" rows="3">Access reliable health resources, wellness tips, and disease prevention guides to make informed decisions about your well-being.</textarea>
                            </div>
                        </div>

                        <div class="card p-3 mb-3">
                            <div class="mb-3">
                                <label class="form-label">Icon class</label>
                                <input type="text" class="form-control" value="bxs-first-aid">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" class="form-control" value="Emergency Assistance">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" rows="3">Quickly connect with medical professionals in urgent situations and receive immediate guidance on first-aid measures and next steps.</textarea>
                            </div>
                        </div>

                        <div class="card p-3 mb-3">
                            <div class="mb-3">
                                <label class="form-label">Icon class</label>
                                <input type="text" class="form-control" value="bxs-file-plus">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" class="form-control" value="E-Prescriptions">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" rows="3">Receive digital prescriptions directly from doctors, ensuring safe and convenient access to medications without visiting a clinic.</textarea>
                            </div>
                        </div>

                        <!-- <h4 class="mt-4">Opening Hours</h4>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Weekday Hours</label>
                                <select class="form-select">
                                    <option selected>7:00 AM - 6:00 PM</option>
                                    <option>8:00 AM - 5:00 PM</option>
                                    <option>9:00 AM - 4:00 PM</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Weekend Hours</label>
                                <select class="form-select">
                                    <option selected>7:00 AM - 4:00 PM</option>
                                    <option>8:00 AM - 3:00 PM</option>
                                    <option>9:00 AM - 2:00 PM</option>
                                </select>
                            </div>
                        </div> -->
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary text-light">Save</button>
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
                        <form>
                            <div class="mb-3">
                                <label for="subtitle" class="form-label text-secondary">Subtitle</label>
                                <input type="text" class="form-control" id="subtitle" value="Learn more about us">
                            </div>
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title" value="Your Health, Anytime, Anywhere">
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" rows="3">Welcome to University Telecommunications Health Services! We are dedicated to enhancing student well-being through innovative, remote health solutions that ensure accessibility, privacy, and high-quality care.</textarea>
                            </div>

                            <label class="form-label">Key Points</label>
                            <div class="mb-3 d-flex align-items-center">
                                <input type="text" class="form-control" value="Revolutionizing healthcare accessibility through telecommunication.">
                            </div>
                            <div class="mb-3 d-flex align-items-center">
                                <input type="text" class="form-control" value="Ensuring students receive quality healthcare, regardless of location.">
                            </div>
                            <div class="mb-3 d-flex align-items-center">
                                <input type="text" class="form-control" value="Empowering students with tools and resources for better health management.">
                            </div>
                            <div class="mb-3">
                                <label for="imageUpload" class="form-label">Upload Image</label>
                                <input type="file" class="form-control" id="image3" onchange="previewImage(event, 'preview4')">
                                <img id="preview4" src="../assets/images/services/counseling_services.png" class="card-img-top" style="height: 250px; object-fit: cover;">

                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary text-light">Save</button>
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