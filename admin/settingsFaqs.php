<?php
session_start();

if (isset($_SESSION['verification_status']) && $_SESSION['verification_status'] != 'Verified') {
    header('location: ../user/verification.php');
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 0) {
    header('location: ../index.php');
}

require_once('../classes/faqs.class.php');

$faq = new FAQ();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_faq'])) {
        if ($faq->addFAQ($_POST['question'], $_POST['answer'])) {
            $_SESSION['message'] = 'FAQ added successfully!';
        } else {
            $_SESSION['error'] = 'Failed to add FAQ.';
        }
    } elseif (isset($_POST['edit_faq'])) {
        if ($faq->updateFAQ($_POST['id'], $_POST['question'], $_POST['answer'])) {
            $_SESSION['message'] = 'FAQ updated successfully!';
        } else {
            $_SESSION['error'] = 'Failed to update FAQ.';
        }
    } elseif (isset($_POST['delete_faq'])) {
        if ($faq->deleteFAQ($_POST['id'])) {
            $_SESSION['message'] = 'FAQ deleted successfully!';
        } else {
            $_SESSION['error'] = 'Failed to delete FAQ.';
        }
    }
    header('location: admin_faqs.php');
    exit();
}

$faqs = $faq->getFAQs();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    $title = 'Admin | FAQs';
    include './includes/admin_head.php';
    ?>
</head>

<body>
    <?php
    require_once('./includes/admin_header.php');
    ?>
    <?php
    require_once('./includes/admin_sidepanel.php');
    ?>

    <section id="userPage" class="page-container">
        <h1 class="text-start mb-3">Manage FAQs</h1>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['message'];
                                                unset($_SESSION['message']); ?></div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['error'];
                                            unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <!-- Add FAQ Section -->
        <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#addFAQSection" aria-expanded="false" aria-controls="addFAQSection">
            <div class="d-flex flex-row align-items-center">
                <h5 class="card-title">Add New FAQ</h5>
                <i id="chevronIconAddFAQ" class='bx bxs-chevron-down ms-2'></i>
            </div>
        </button>
        <hr class="mt-1 mb-2">
        <div class="collapse" id="addFAQSection">
            <div class="card mb-3 w-100">
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label for="question" class="form-label">Question</label>
                            <input type="text" class="form-control" name="question" placeholder="Enter question" required>
                        </div>
                        <div class="mb-3">
                            <label for="answer" class="form-label">Answer</label>
                            <textarea class="form-control" name="answer" rows="3" placeholder="Enter answer" required></textarea>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" name="add_faq" class="btn btn-primary text-light">Add FAQ</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- List of FAQs -->
        <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#faqListSection" aria-expanded="false" aria-controls="faqListSection">
            <div class="d-flex flex-row align-items-center">
                <h5 class="card-title">FAQs List</h5>
                <i id="chevronIconFAQList" class='bx bxs-chevron-down ms-2'></i>
            </div>
        </button>
        <hr class="mt-1 mb-2">
        <div class="collapse show" id="faqListSection">
            <div class="card w-100">
                <div class="card-body">
                    <ul class="list-unstyled">
                        <?php foreach ($faqs as $faq): ?>
                            <li class="mb-3">
                                <form method="POST" class="row g-3">
                                    <input type="hidden" name="id" value="<?php echo $faq['id']; ?>">
                                    <div class="col-md-5">
                                        <label for="question" class="form-label">Question</label>
                                        <input type="text" class="form-control" name="question" value="<?php echo htmlspecialchars($faq['question']); ?>">
                                    </div>
                                    <div class="col-md-5">
                                        <label for="answer" class="form-label">Answer</label>
                                        <textarea class="form-control" name="answer" rows="3"><?php echo htmlspecialchars($faq['answer']); ?></textarea>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="submit" name="edit_faq" class="btn btn-primary btn-sm text-light me-2">Edit</button>
                                        <button type="submit" name="delete_faq" class="btn btn-danger btn-sm text-light">Delete</button>
                                    </div>
                                </form>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add FAQ Section
            const addFAQButton = document.querySelector('[data-bs-target="#addFAQSection"]');
            const chevronIconAddFAQ = document.getElementById('chevronIconAddFAQ');

            addFAQButton.addEventListener('click', function() {
                chevronIconAddFAQ.classList.toggle('rotate-180');
            });

            // FAQ List Section
            const faqListButton = document.querySelector('[data-bs-target="#faqListSection"]');
            const chevronIconFAQList = document.getElementById('chevronIconFAQList');

            faqListButton.addEventListener('click', function() {
                chevronIconFAQList.classList.toggle('rotate-180');
            });
        });
    </script>
</body>

</html>