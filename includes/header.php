<header class="navbar fixed-top p-2 p-lg-3 navbar-expand-lg navbar-dark bg-white shadow-sm">
	<div class="container-fluid">

		<!-- Display profile dropdown for mobile if the user is logged in -->
		<a href="#" class="<?= isset($_SESSION['user_role']) ? "d-sm-block" : "d-none" ?> d-lg-none acc link-dark text-decoration-none dropdown-toggle ms-2" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
			<div class="d-flex align-items-center">
				<img src="./../assets/images/default_profile.png" alt="mdo" width="32" height="32" class="rounded-circle border border-2 border-light me-2">
				<h6 class="m-0 text-dark d-none d-md-block"><?= $_SESSION['fullname'] ?></h6>
			</div>
		</a>

		<ul class="dropdown-menu text-small" aria-labelledby="profileDropddown">
			<li>
				<h4 class="m-0 text-dark d-block d-lg-none text-center"><?= $_SESSION['fullname'] ?></h4>
			</li>
			<li>
				<hr class="dropdown-divider d-block d-lg-none">
			</li>
			<li><a class="dropdown-item" href="./profile_general.php">Profile</a></li>
			<li><a class="dropdown-item" href="./chat_user.php">Chat</a></li>
			<li>
				<hr class="dropdown-divider m-0 mt-1">
			</li>
			<li><a class="dropdown-item" href="../logout">Logout</a></li>
		</ul>

		<a class="navbar-brand d-flex align-items-center text-dark text-decoration-none" href="../index.php">
			<img src="../assets/images/logo.png" alt="Logo" height="35">
			<h1 class="fs-4 link-primary m-0 d-name">Doc<span class="link-dark">Connect</span></h1>
		</a>

		<button class="navbar-toggler bg-primary" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
			<ul class="navbar-nav users_nav mb-2 mb-lg-0 header nav nav-pills">
				<li class="nav-item"><a href="./about_us" class="nav-link text-primary d-flex justify-content-center	<?= $about ?>"><span class="text">About Us</span></a></li>
				<li class="nav-item"><a href="./services" class="nav-link text-primary d-flex justify-content-center	<?= $services ?>"><span class="text">Services</span></a></li>
				<li class="nav-item"><a href="./doctors" class="nav-link text-primary d-flex justify-content-center	<?= $doctors ?>"><span class="text">Our Doctors</span></a></li>
				<li class="nav-item"><a href="./appointment" class="nav-link text-primary d-flex justify-content-center	<?= $appointment ?>"><span class="text">Appointment</span></a></li>
				<li class="nav-item"><a href="./contact" class="nav-link text-primary d-flex justify-content-center	<?= $contact ?>"><span class="text">Contact</span></a></li>
				<li class="nav-item"><a href="./login" class="nav-link px-3 rounded-1 ms-0 ms-md-2 bg-green text-white <?= isset($_SESSION['user_role']) ? "d-none" : 'd-block' ?>" aria-current="page">Login</a></li>

				<li class="nav-item dropdown <?= isset($_SESSION['user_role']) ? "d-lg-flex d-none" : '' ?> align-items-center border-start ms-2">
					<a href="#" class="acc d-block link-dark text-decoration-none dropdown-toggle ms-2" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
						<div class="d-flex align-items-center">
							<img src="<?php if (isset($_SESSION['account_image'])) {
											echo "../assets/images/" . $_SESSION['account_image'];
										} else {
											echo "../assets/images/bg-1.png";
										} ?>" alt="mdo" width="32" height="32" class="rounded-circle border border-2 border-light me-2">
							<h6 class="m-0 text-dark d-none d-md-block"><?= $_SESSION['fullname'] ?></h6>
						</div>
					</a>
					<ul class="dropdown-menu text-small" aria-labelledby="profileDropddown">
						<li>
							<h4 class="m-0 text-dark d-block d-lg-none text-center"><?= $_SESSION['fullname'] ?></h4>
						</li>
						<li>
							<hr class="dropdown-divider d-block d-lg-none">
						</li>
						<li><a class="dropdown-item" href="./profile_general.php">Profile</a></li>
						<li><a class="dropdown-item" href="./chat_user.php">Chat</a></li>
						<li>
							<hr class="dropdown-divider m-0 mt-1">
						</li>
						<li><a class="dropdown-item	" href="../logout">Logout</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
</header>