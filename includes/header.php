<header class="navbar fixed-top p-2 p-lg-3 navbar-expand-lg navbar-dark bg-white shadow-sm">
	<div class="container-fluid">
		<!-- Display profile dropdown for mobile if the user is logged in -->
		<a href="#" class="<?= isset($_SESSION['user_role']) ? "d-sm-block" : "d-none" ?> d-lg-none acc link-dark text-decoration-none dropdown-toggle ms-2" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
			<div class="d-flex align-items-center">
				<img src="<?php if (isset($_SESSION['account_image'])) {
								echo "../assets/images/" . $_SESSION['account_image'];
							} else {
								echo "../assets/images/default_profile.png";
							} ?>" alt="mdo" width="37" height="37" class="rounded-circle border border-2 border-light me-2">
			</div>
		</a>

		<ul class="dropdown-menu ms-3 text-small" aria-labelledby="profileDropddown">
			<li>
				<p class="m-0 text-dark d-block d-lg-none text-center"><?= $_SESSION['fullname'] ?></p>
			</li>
			<hr class="dropdown-divider mx-2 mt-1">
			<li><a class="dropdown-item" href="./profile_general.php">Profile</a></li>
			<li><a class="dropdown-item" href="./chat_user.php">Chat</a></li>
			<hr class="dropdown-divider mx-2 mt-1">
			<li><a class="dropdown-item" href="../logout">Logout</a></li>
		</ul>

		<a class="navbar-brand d-flex align-items-center text-dark text-decoration-none" href="./index">
			<img src="../assets/images/logo.png" alt="Logo" height="35">
			<h1 class="fs-4 link-primary m-0 d-name">Doc<span class="link-dark">Connect</span></h1>
		</a>

		<button class="navbar-toggler bg-primary" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" style="padding: 5px 10px;">
			<span class="navbar-toggler-icon" style="width: 1em; height: 1em;"></span>
		</button>

		<div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
			<ul class="navbar-nav users_nav mb-2 mb-lg-0 header nav nav-pills">
				<li class="nav-item"><a href="./about_us" class="nav-link text-primary d-flex justify-content-center <?= $about ?>"><span class="text">About Us</span></a></li>
				<li class="nav-item"><a href="./services" class="nav-link text-primary d-flex justify-content-center <?= $services ?>"><span class="text">Services</span></a></li>
				<li class="nav-item"><a href="./doctors" class="nav-link text-primary d-flex justify-content-center <?= $doctors ?>"><span class="text">Our Doctors</span></a></li>
				<li class="nav-item"><a href="./appointment" class="nav-link text-primary d-flex justify-content-center	<?= $appointment ?>"><span class="text">Appointment</span></a></li>
				<li class="nav-item"><a href="./contact" class="nav-link text-primary d-flex justify-content-center	<?= $contact ?>"><span class="text">Contact</span></a></li>
				<li class="nav-item"><a href="./login" class="nav-link px-3 rounded-1 ms-0 ms-md-2 bg-green text-white <?= isset($_SESSION['user_role']) ? "d-none" : 'd-block' ?>" aria-current="page">Login</a></li>

				<li class="nav-item dropdown d-none <?= isset($_SESSION['user_role']) ? "d-lg-flex" : 'd-none' ?> align-items-center border-start ms-2">
					<a href="#" class="acc d-block link-dark text-decoration-none dropdown-toggle ms-2" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
						<div class="d-flex align-items-center">
							<img src="<?php if (isset($_SESSION['account_image'])) {
											echo "../assets/images/" . $_SESSION['account_image'];
										} else {
											echo "../assets/images/default_profile.png";
										} ?>" alt="mdo" width="32" height="32" class="ms-2 rounded-circle border border-2 border-light">
							<!-- <h6 class="m-0 text-dark d-none d-md-block"><?= $_SESSION['fullname'] ?></h6> -->

						</div>
					</a>
					<ul class="dropdown-menu dropdown-menu-end text-small" aria-labelledby="profileDropddown">
						<li>
							<p class="m-0 mx-2 text-dark d-block text-center text-green fw-bold"><?= $_SESSION['fullname'] ?></p>
						<li>
							<hr class="dropdown-divider mx-2 mt-1">
						<li><a class="dropdown-item" href="./profile_general.php">Profile</a></li>
						<li><a class="dropdown-item" href="./chat_user.php">Chat</a></li>

						<hr class="dropdown-divider mx-2 mt-1">

						<li><a class="dropdown-item" href="../logout">Logout</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
</header>

<script>
	document.addEventListener("DOMContentLoaded", function() {
		// Function to hide all dropdowns
		function hideDropdowns(except = null) {
			const dropdowns = document.querySelectorAll(".dropdown-menu.show");
			dropdowns.forEach((menu) => {
				// Skip hiding the current dropdown
				if (menu === except) return;

				const dropdownToggle = menu.parentElement.querySelector(".dropdown-toggle");
				if (dropdownToggle) {
					bootstrap.Dropdown.getInstance(dropdownToggle)?.hide();
				}
			});
		}

		// Handle clicks outside dropdowns to close them
		document.addEventListener("click", (event) => {
			const dropdownMenu = event.target.closest(".dropdown-menu");
			const dropdownToggle = event.target.closest(".dropdown-toggle");
			const navbarCollapse = document.querySelector("#navbarSupportedContent");
			const navbarToggler = document.querySelector(".navbar-toggler");

			// Close all dropdowns if clicked outside
			if (!dropdownMenu && !dropdownToggle) {
				hideDropdowns();

				// Collapse the navbar if it's open
				if (navbarCollapse.classList.contains("show")) {
					navbarToggler.setAttribute("aria-expanded", "false");
					navbarCollapse.classList.remove("show");
				}
			}
		});

		// Handle scroll to close all dropdowns
		window.addEventListener("scroll", () => {
			hideDropdowns();

			const navbarCollapse = document.querySelector("#navbarSupportedContent");
			const navbarToggler = document.querySelector(".navbar-toggler");

			// Collapse the navbar if it's open on scroll
			if (navbarCollapse.classList.contains("show")) {
				navbarToggler.setAttribute("aria-expanded", "false");
				navbarCollapse.classList.remove("show");
			}
		});

		// Ensure dropdown works properly when clicked
		document.querySelectorAll(".dropdown-toggle").forEach((dropdownToggle) => {
			dropdownToggle.addEventListener("click", (event) => {
				event.stopPropagation(); // Prevent conflicts
				const dropdown = bootstrap.Dropdown.getOrCreateInstance(dropdownToggle);
				const isShown = dropdownToggle.getAttribute("aria-expanded") === "true";

				// Close other dropdowns, keeping this one open
				hideDropdowns(isShown ? null : dropdownToggle.nextElementSibling);
			});
		});

		// Collapse navbar when profile dropdown is clicked
		const profileDropdown = document.querySelector("#dropdownUser1");
		const navbarToggler = document.querySelector(".navbar-toggler");
		const navbarCollapse = document.querySelector("#navbarSupportedContent");

		profileDropdown?.addEventListener("click", () => {
			if (navbarCollapse.classList.contains("show")) {
				// Collapse the navbar
				navbarToggler.setAttribute("aria-expanded", "false");
				navbarCollapse.classList.remove("show");
			}
		});
	});
</script>