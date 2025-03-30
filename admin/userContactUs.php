<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
    header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 0) {
    header('location: ../index.php');
}

require_once('../classes/contact.class.php');
$contactInfo = new ContactInfo();
$currentContactInfo = $contactInfo->getContactInfo();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'heading' => $_POST['heading'],
        'description' => $_POST['description'],
        'address' => $_POST['address'],
        'email' => $_POST['email'],
        'phone' => $_POST['phone'],
        'fax' => $_POST['fax'],
        'facebook_link' => $_POST['facebook_link'],
        'instagram_link' => $_POST['instagram_link'],
        'map_embed_url' => $_POST['map_embed_url']
    ];

    if ($contactInfo->updateContactInfo($data)) {
        $_SESSION['message'] = 'Contact information updated successfully!';
    } else {
        $_SESSION['error'] = 'Failed to update contact information.';
    }
    header('location: userContactUs.php');
    exit();
}

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

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#contactInformation" aria-expanded="false" aria-controls="contactInformation">
            <h5 class="card-title">Contact Information</h5>
        </button>
        <hr class="mt-1 mb-2">
        <div class="collapse show" id="contactInformation">
            <div class="card mb-3 w-100">
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label for="heading" class="form-label">Heading</label>
                            <input type="text" class="form-control" name="heading" value="<?php echo htmlspecialchars($currentContactInfo['heading'] ?? 'Get in touch'); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="2" required><?php echo htmlspecialchars($currentContactInfo['description'] ?? 'Want to get in touch? Wed love to hear from you. Heres how you can reach us.'); ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Head Office Address</label>
                            <input type="text" class="form-control" name="address" value="<?php echo htmlspecialchars($currentContactInfo['address'] ?? 'Normal Road, Baliwasan, 7000 Zamboanga City'); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($currentContactInfo['email'] ?? 'wmsu.docconnect@gmail.com'); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" name="phone" value="<?php echo htmlspecialchars($currentContactInfo['phone'] ?? '+639 919 309'); ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="fax" class="form-label">Fax</label>
                            <input type="text" class="form-control" name="fax" value="<?php echo htmlspecialchars($currentContactInfo['fax'] ?? '+63629 924 238'); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="facebook_link" class="form-label">Facebook Link</label>
                            <input type="url" class="form-control" name="facebook_link" value="<?php echo htmlspecialchars($currentContactInfo['facebook_link'] ?? '#'); ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="instagram_link" class="form-label">Instagram Link</label>
                            <input type="url" class="form-control" name="instagram_link" value="<?php echo htmlspecialchars($currentContactInfo['instagram_link'] ?? '#'); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="map_embed_url" class="form-label">Google Map Embed URL</label>
                            <input type="text" class="form-control" name="map_embed_url" value="<?php echo htmlspecialchars($currentContactInfo['map_embed_url'] ?? 'https://www.google.com/maps/embed?pb=...'); ?>" required>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary text-light">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Preview Section -->
        <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#previewSection" aria-expanded="false" aria-controls="previewSection">
            <h5 class="card-title">Preview</h5>
        </button>
        <hr class="mt-1 mb-2">
        <div class="collapse" id="previewSection">
            <div class="card mb-3 w-100">
                <div class="card-body">
                    <div class="map-container">
                        <iframe id="mapIframe" src="<?php echo htmlspecialchars($currentContactInfo['map_embed_url'] ?? 'https://www.google.com/maps/embed?pb=...'); ?>" width="100%" height="400" style="border:0;" allowfullscreen loading="lazy"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
</html>