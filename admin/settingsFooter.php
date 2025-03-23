<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
    header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 0) {
    header('location: ../index.php');
}

require_once('../classes/footer.class.php');

$footerContent = new FooterContent();
$currentFooterContent = $footerContent->getFooterContent();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $description = $_POST['description'];
    $address = $_POST['address'];
    $phone_numbers = $_POST['phone_numbers'];
    $facebook_link = $_POST['facebook_link'];
    $gmail_link = $_POST['gmail_link'];

    if ($footerContent->updateFooterContent($description, $address, $phone_numbers, $facebook_link, $gmail_link)) {
        $_SESSION['message'] = 'Footer content updated successfully!';
    } else {
        $_SESSION['error'] = 'Failed to update footer content.';
    }
    header('location: settingsFooter.php');
    exit();
}

?>
<html lang="en">
<?php
$title = 'Admin | Footer Settings';
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
        $footer = 'active';
        $aFooter = 'page';
        $cFooter = 'text-dark';

        include './includes/adminSettings_nav.php';
        ?>

        <h1 class="text-start mb-3">Footer Settings</h1>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['message'];
                                                unset($_SESSION['message']); ?></div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['error'];
                                            unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <!-- Footer Content Section -->
        <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#footerContentSection" aria-expanded="false" aria-controls="footerContentSection">
            <div class="d-flex flex-row align-items-center">
                <h5 class="card-title">Footer Content</h5>
                <i id="chevronIconFooter" class='bx bxs-chevron-down ms-2'></i>
            </div>
        </button>
        <hr class="mt-1 mb-2">
        <div class="collapse" id="footerContentSection">
            <div class="card mb-3 w-100">
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3" required><?php echo htmlspecialchars($currentFooterContent['description'] ?? ''); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" name="address" value="<?php echo htmlspecialchars($currentFooterContent['address'] ?? ''); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone_numbers" class="form-label">Phone Numbers</label>
                            <input type="text" class="form-control" name="phone_numbers" value="<?php echo htmlspecialchars($currentFooterContent['phone_numbers'] ?? ''); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="facebook_link" class="form-label">Facebook Link</label>
                            <input type="url" class="form-control" name="facebook_link" value="<?php echo htmlspecialchars($currentFooterContent['facebook_link'] ?? ''); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="gmail_link" class="form-label">Gmail Link</label>
                            <input type="url" class="form-control" name="gmail_link" value="<?php echo htmlspecialchars($currentFooterContent['gmail_link'] ?? ''); ?>">
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
        document.addEventListener('DOMContentLoaded', function() {
            const footerButton = document.querySelector('[data-bs-target="#footerContentSection"]');
            const chevronIconFooter = document.getElementById('chevronIconFooter');

            footerButton.addEventListener('click', function() {
                chevronIconFooter.classList.toggle('rotate-180');
            });
        });
    </script>
</body>


</html>