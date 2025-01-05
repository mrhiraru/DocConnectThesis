<nav id="sidebarMenu" class="bg-white fixed-top col-md-3 col-lg-2 d-md-block border-end sidebar collapse min-vh-100 p-0">
    <div class="d-flex flex-column h-100">
        <div class="pt-3 mb-2 d-flex align-items-center flex-column">
            <img src="<?php if (isset($_SESSION['account_image'])) {
                            echo "../assets/images/" . $_SESSION['account_image'];
                        } else {
                            echo "../assets/images/defualt_profile.png";
                        } ?>" alt="" height="100" width="100" class="rounded rounded-circle border border-2 border-primary mb-2">
            <h5 class="text-primary fw-semibold mb-1"><?= $_SESSION['fullname'] ?></h5>
            <p class="text-muted fw-light mb-0"><?= $_SESSION['specialty'] ?></p>
        </div>

        <hr class="m-0 mb-2 mx-3 border-primary">

        <ul class="nav doctors_nav flex-column mb-auto">
            <li class="nav-item">
                <a class="nav-link text-dark <?= $dashboard ?>" href="../doctor/index">
                    <i class='bx bx-bar-chart-square me-2'></i>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark <?= $profile ?>" href="../doctor/profile">
                    <i class='bx bxs-user-detail me-2'></i>
                    Profile
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark <?= $appointment ?>" href="../doctor/appointment">
                    <i class='bx bx-book-bookmark me-2'></i>
                    Appointment
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark <?= $patient ?>" href="../doctor/patients">
                    <i class='bx bx-group me-2'></i>
                    Patients
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark <?= $chat ?>" href="../doctor/chats">
                    <i class='bx bx-chat me-2'></i>
                    Chats
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark <?= $notification ?>" href="../doctor/notifications">
                    <i class='bx bx-bell me-2'></i>
                    Notification
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark <?= $setting ?>" href="../doctor/settings_profile">
                    <i class='bx bx-cog me-2'></i>
                    Settings
                </a>
            </li>
        </ul>

        <div class="logout-section mt-auto">
            <hr class="mx-3 my-1 border-primary">
            <ul class="nav doctors_nav flex-column">
                <li class="nav-item">
                    <a class="nav-link text-dark" href="../logout">
                        <i class='bx bx-log-out me-2'></i>
                        Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>