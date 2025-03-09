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
$title = 'Admin | User Contact us';
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
        $contactUs = 'active';
        $aContactUs = 'page';
        $cContactUs = 'text-dark';

        include './includes/adminUserPage_nav.php';
        ?>

        <h1 class="text-start mb-3">User Contact us</h1>
        <h6 class="text-start mb-4 text-muted">Icon Class: <a href="https://boxicons.com/" target="_blank">Boxicons.com</a></h6>

        <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#contactInformation" aria-expanded="false" aria-controls="contactInformation">
            <h5 class="card-title">Contact Information</h5>
        </button>
        <hr class="mt-1 mb-2">
        <div class="collapse" id="contactInformation">
            <div class="card mb-3 w-100">
                <div class="card-body">
                    <!-- Admin Edit Form for Contact Details -->
                    <div class="col-md-12 rounded-3 mb-4 mb-md-0 p-4" style="background-color: #eeeeee;">
                        <h4>Edit Contact Details</h4>
                        <form>
                            <div class="mb-3">
                                <label for="heading" class="form-label">Heading</label>
                                <input type="text" class="form-control" id="heading" value="Get in touch">
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" rows="2">Want to get in touch? We’d love to hear from you. Here’s how you can reach us.</textarea>
                            </div>

                            <!-- Address -->
                            <div class="mb-3">
                                <label for="address" class="form-label">Head Office Address</label>
                                <input type="text" class="form-control" id="address" value="Normal Road, Baliwasan, 7000 Zamboanga City">
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" value="wmsu.docconnect@gmail.com">
                            </div>

                            <!-- Phone and Fax -->
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="phone" value="+639 919 309">
                            </div>
                            <div class="mb-3">
                                <label for="fax" class="form-label">Fax</label>
                                <input type="text" class="form-control" id="fax" value="+63629 924 238">
                            </div>

                            <!-- Social Media Links -->
                            <div class="mb-3">
                                <label for="facebook" class="form-label">Facebook Link</label>
                                <input type="url" class="form-control" id="facebook" value="#">
                            </div>
                            <div class="mb-3">
                                <label for="instagram" class="form-label">Instagram Link</label>
                                <input type="url" class="form-control" id="instagram" value="#">
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary text-light">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#googleMap" aria-expanded="false" aria-controls="googleMap">
            <h5 class="card-title">Google Map</h5>
        </button>
        <hr class="mt-1 mb-2">
        <div class="collapse" id="googleMap">
            <div class="card mb-3 w-100">
                <div class="card-body">
                    <!-- Bootstrap 5 Form for Editing Map Embed URL -->
                    <form id="mapForm" class="p-3 border rounded shadow-sm">
                        <div class="mb-3">
                            <label for="mapSrc" class="form-label">Google Map Embed URL</label>
                            <input type="text" class="form-control" id="mapSrc" name="mapSrc" value="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7921.61518569353!2d122.05236037770997!3d6.913594200000012!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x325041dd7a24816f%3A0x51af215fb64cc81a!2sWestern%20Mindanao%20State%20University!5e0!3m2!1sen!2sph!4v1734515759093!5m2!1sen!2sph" required>
                        </div>
                        <button type="button" class="btn btn-primary text-light" onclick="updateMap()">Update Map</button>
                    </form>

                    <!-- Map Section -->
                    <div class="map-container mt-3">
                        <iframe id="mapIframe" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7921.61518569353!2d122.05236037770997!3d6.913594200000012!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x325041dd7a24816f%3A0x51af215fb64cc81a!2sWestern%20Mindanao%20State%20University!5e0!3m2!1sen!2sph!4v1734515759093!5m2!1sen!2sph" width="100%" height="400" style="border:0;" allowfullscreen loading="lazy"></iframe>
                    </div>

                </div>
            </div>
        </div>
    </section>


    <script>
        function updateMap() {
            const mapSrc = document.getElementById("mapSrc").value;
            document.getElementById("mapIframe").src = mapSrc;
        }
    </script>
</body>

</html>