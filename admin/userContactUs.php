<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
    header('location: ../user/verification.php');
    exit();
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 0) {
    header('location: ../index.php');
    exit();
}

require_once('../classes/contact.class.php');
$contactInfo = new ContactInfo();
$currentContactInfo = $contactInfo->getContactInfo();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'heading' => $_POST['heading'] ?? '',
        'description' => $_POST['description'] ?? '',
        'address' => $_POST['address'] ?? '',
        'email' => $_POST['email'] ?? '',
        'phone' => $_POST['phone'] ?? '',
        'fax' => $_POST['fax'] ?? '',
        'facebook_link' => $_POST['facebook_link'] ?? '#',
        'instagram_link' => $_POST['instagram_link'] ?? '#',
        'map_embed_url' => $_POST['map_embed_url'] ?? ''
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
<!DOCTYPE html>
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

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['message'];
                                                unset($_SESSION['message']); ?></div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['error'];
                                            unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <div class="card mb-4 bg-light">
            <div class="card-body">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="heading" class="form-label">Heading</label>
                        <input type="text" class="form-control" id="heading" name="heading"
                            value="<?php echo htmlspecialchars($currentContactInfo['heading'] ?? 'Get in touch'); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required><?php
                                                                                                                echo htmlspecialchars($currentContactInfo['description'] ?? 'Want to get in touch? We\'d love to hear from you. Here\'s how you can reach us.');
                                                                                                                ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="address" name="address"
                                value="<?php echo htmlspecialchars($currentContactInfo['address'] ?? 'Normal Road, Baliwasan, 7000 Zamboanga City'); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                value="<?php echo htmlspecialchars($currentContactInfo['email'] ?? 'wmsu.docconnect@gmail.com'); ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone"
                                value="<?php echo htmlspecialchars($currentContactInfo['phone'] ?? '+639 919 309'); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="fax" class="form-label">Fax</label>
                            <input type="text" class="form-control" id="fax" name="fax"
                                value="<?php echo htmlspecialchars($currentContactInfo['fax'] ?? '+63629 924 238'); ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="facebook_link" class="form-label">Facebook Link</label>
                            <input type="url" class="form-control" id="facebook_link" name="facebook_link"
                                value="<?php echo htmlspecialchars($currentContactInfo['facebook_link'] ?? '#'); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="instagram_link" class="form-label">Instagram Link</label>
                            <input type="url" class="form-control" id="instagram_link" name="instagram_link"
                                value="<?php echo htmlspecialchars($currentContactInfo['instagram_link'] ?? '#'); ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="map_embed_url" class="form-label">Google Maps Embed URL</label>
                        <textarea class="form-control" id="map_embed_url" name="map_embed_url" rows="3" required><?php
                                                                                                                    echo htmlspecialchars($currentContactInfo['map_embed_url'] ?? '');
                                                                                                                    ?></textarea>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary px-4 text-light">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Map Preview</h5>
            </div>
            <div class="card-body">
                <div class="ratio ratio-16x9">
                    <iframe src="<?php echo htmlspecialchars($currentContactInfo['map_embed_url'] ?? ''); ?>"
                        allowfullscreen loading="lazy"></iframe>
                </div>
            </div>
        </div>
    </section>

    <?php include './includes/admin_footer.php'; ?>
</body>

</html>