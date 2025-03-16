<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQs - DocConnect</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->

    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .faq-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .faq-item {
            margin-bottom: 15px;
            border-bottom: 1px solid #ddd;
        }

        .faq-question {
            width: 100%;
            text-align: left;
            padding: 15px;
            font-size: 18px;
            font-weight: bold;
            background-color: #f1f1f1;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .faq-question:hover {
            background-color: #ddd;
        }

        .faq-answer {
            padding: 15px;
            display: none;
            /* Hidden by default */
            background-color: #f9f9f9;
            border-radius: 5px;
        }

        .faq-answer p {
            margin: 0;
        }

        .faq-question {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .icon {
            font-size: 20px;
            font-weight: bold;
        }

        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }
    </style>
</head>

<body>
    <header>
        <h1>Frequently Asked Questions</h1>
        <p>Find answers to common questions about DocConnect.</p>
    </header>

    <main class="faq-container">
        <!-- FAQ Item 1 -->
        <div class="faq-item">
            <button class="faq-question">What is DocConnect?</button>
            <div class="faq-answer">
                <p>DocConnect is a platform that offers free telecommunication health services to university students. It allows students to consult with healthcare professionals remotely.</p>
            </div>
        </div>

        <!-- FAQ Item 2 -->
        <div class="faq-item">
            <button class="faq-question">How do I sign up for DocConnect?</button>
            <div class="faq-answer">
                <p>To sign up, visit our website and click on the "Sign Up" button. Follow the instructions to create your account.</p>
            </div>
        </div>

        <!-- FAQ Item 3 -->
        <div class="faq-item">
            <button class="faq-question">Is DocConnect free?</button>
            <div class="faq-answer">
                <p>Yes, DocConnect is completely free for all university students.</p>
            </div>
        </div>

        <button class="faq-question">
            <span>What is DocConnect?</span>
            <span class="icon">+</span>
        </button>
        <div class="faq-answer">
            <p>DocConnect is a platform that offers free telecommunication health services to university students.</p>
        </div>

        <!-- Add more FAQ items as needed -->
    </main>

    <footer>
        <p>Need more help? <a href="/contact">Contact Us</a></p>
    </footer>

    <script src="script.js"></script> <!-- Link to your JavaScript file -->
    <script>
        // script.js
        document.addEventListener("DOMContentLoaded", function() {
            const faqQuestions = document.querySelectorAll(".faq-question");

            faqQuestions.forEach((question) => {
                question.addEventListener("click", () => {
                    const answer = question.nextElementSibling;
                    answer.style.display = answer.style.display === "block" ? "none" : "block";
                });
            });
        });
        document.addEventListener("DOMContentLoaded", function() {
            const faqQuestions = document.querySelectorAll(".faq-question");

            faqQuestions.forEach((question) => {
                question.addEventListener("click", () => {
                    const answer = question.nextElementSibling;
                    const icon = question.querySelector(".icon");

                    if (answer.style.maxHeight) {
                        answer.style.maxHeight = null;
                        icon.textContent = "+";
                    } else {
                        answer.style.maxHeight = answer.scrollHeight + "px";
                        icon.textContent = "-";
                    }
                });
            });
        });
    </script>
</body>

</html>