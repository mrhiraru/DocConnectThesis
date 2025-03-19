<?php
session_start();
require_once('../classes/faqs.class.php');

$faq = new FAQ();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_faq'])) {
        $faq->addFAQ($_POST['question'], $_POST['answer']);
    } elseif (isset($_POST['edit_faq'])) {
        $faq->updateFAQ($_POST['id'], $_POST['question'], $_POST['answer']);
    } elseif (isset($_POST['delete_faq'])) {
        $faq->deleteFAQ($_POST['id']);
    }
}

$faqs = $faq->getFAQs();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin FAQs</title>
</head>

<body>
    <h1>Manage FAQs</h1>

    <!-- Add FAQ Form -->
    <form method="POST">
        <input type="text" name="question" placeholder="Question" required>
        <textarea name="answer" placeholder="Answer" required></textarea>
        <button type="submit" name="add_faq">Add FAQ</button>
    </form>

    <!-- List of FAQs -->
    <ul>
        <?php foreach ($faqs as $faq): ?>
            <li>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo $faq['id']; ?>">
                    <input type="text" name="question" value="<?php echo htmlspecialchars($faq['question']); ?>">
                    <textarea name="answer"><?php echo htmlspecialchars($faq['answer']); ?></textarea>
                    <button type="submit" name="edit_faq">Edit</button>
                    <button type="submit" name="delete_faq">Delete</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
</body>

</html>