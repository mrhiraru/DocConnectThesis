<style>
  .no-hover {
    pointer-events: none;
    cursor: default;
  }

  .no-hover:hover {
    color: inherit;
    background-color: inherit;
  }

  @media (min-width: 992px) {
    .sidepanel {
      transform: translateX(0) !important;
    }
  }
</style>

<header class="p-3 border-bottom bg-green shadow-sm position-fixed w-100 z-3 border-bottom-0">
  <div class="d-flex flex-wrap justify-content-between align-items-center">
    <button class="navbar-toggler d-lg-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
      <i class='bx bx-menu navbar-toggler-icon pt-2 text-light fs-3'></i>
    </button>

    <a class="d-flex align-items-center text-dark text-decoration-none" href="./index">
      <img src="../assets/images/logo.png" class="me-2" alt="Logo" height="32">
      <h1 class="fs-4 link-light m-0 d-name">Doc<span class="link-light">Connect</span></h1>
    </a>

    <div class="d-flex align-items-center justify-content-between">
      <div class="dropdown me-3 d-none">
        <a href="<?php echo getCurrentPage() == 'notifications.php' ? '#' : 'notifications.php'; ?>"
          class="notification dropdown-toggle text-decoration-none text-dark 
           <?php echo getCurrentPage() == 'notifications.php' ? 'disabled no-hover' : ''; ?>"
          id="notificationDropdown"
          data-bs-toggle="dropdown"
          aria-expanded="false">
          <i class='bx bx-bell fs-5 p-2 <?php echo getCurrentPage() == 'notifications.php' ? 'link-light bg-light text-dark rounded-5' : 'link-light'; ?>'></i>
        </a>
        <ul class="notification dropdown-menu dropdown-menu-end px-0" aria-labelledby="notificationDropdown">
          <li class="header mx-3 d-flex align-items-center justify-content-between">
            <h4>Notification</h4>
            <div class="dropdown">
              <i class='bx bx-dots-horizontal-rounded fs-4' id="dotsDropdown" data-bs-toggle="dropdown" aria-expanded="false"></i>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dotsDropdown">
                <li><a class="dropdown-item mark-all" href="#">Mark all as read</a></li>
              </ul>
            </div>
          </li>

          <hr class="mx-3">

          <li class="dropdown-item d-flex align-items-center">
            <i class='bx bx-check-circle me-2 fs-2 text-success'></i>
            <div>
              <div class="fw-bold">Operation Successful</div>
              <small class="text-muted">Your request was processed successfully.</small>
            </div>
          </li>
          <li class="dropdown-item d-flex align-items-center">
            <i class='bx bx-x-circle me-2 fs-2 text-danger'></i>
            <div>
              <div class="fw-bold">Operation Failed</div>
              <small class="text-muted">There was an error processing your request.</small>
            </div>
          </li>
          <li class="dropdown-item d-flex align-items-center">
            <i class='bx bx-error-circle me-2 fs-2 text-warning'></i>
            <div>
              <div class="fw-bold">Warning</div>
              <small class="text-muted">Please check the details carefully.</small>
            </div>
          </li>
          <li class="dropdown-item d-flex align-items-center">
            <i class='bx bx-info-circle me-2 fs-2 text-info'></i>
            <div>
              <div class="fw-bold">Information</div>
              <small class="text-muted">Here is some important information.</small>
            </div>
          </li>
          <li class="dropdown-item d-flex align-items-center">
            <i class='bx bx-alarm me-2 fs-2 text-warning'></i>
            <div>
              <div class="fw-bold">Reminder</div>
              <small class="text-muted">Lorem ipsum dolor sit amet consectetur.</small>
            </div>
          </li>
          <li class="dropdown-item d-flex align-items-center">
            <i class='bx bx-envelope me-2 fs-2 text-blue'></i>
            <div>
              <div class="fw-bold">Message</div>
              <small class="text-muted">Lorem ipsum dolor sit amet consectetur.</small>
            </div>
          </li>

          <hr class="mx-3">

          <li class="show-all dropdown-item bg-white">
            <a href="./notifications">
              <button type="button" class="btn btn-outline-primary w-100">Show All Activities</button>
            </a>
          </li>
        </ul>
      </div>

      <div class="dropdown text-end">
        <a href="#" class="acc d-block link-dark text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
          <div class="d-flex align-items-center">
            <img src="../../assets/images/default_profile.png" alt="mdo" width="32" height="32" class="rounded-circle border border-2 border-light me-2">
            <h6 class="m-0 text-light d-none d-md-block"><?= $_SESSION['campus_name'] ?> Moderator</h6>
          </div>
        </a>
        <ul class="dropdown-menu text-small" aria-labelledby="profileDropddown">
          <li>
            <h4 class="m-0 text-dark d-block d-lg-none text-center"><?= $_SESSION['email'] ?></h4>
          </li>
          <li>
            <hr class="dropdown-divider d-block d-lg-none">
          </li>
          <li><a class="dropdown-item" href="../logout">Logout</a></li>
        </ul>
      </div>
    </div>

  </div>
</header>

<script>
  document.querySelector('.navbar-toggler').addEventListener('click', function() {
    const sidepanel = document.querySelector('.sidepanel');
    if (window.innerWidth < 991) {
      sidepanel.classList.toggle('show');
      document.body.classList.toggle('sidepanel-open');
    }
  });
</script>

<script src="./../js/notification_dropdropdown.js"></script>