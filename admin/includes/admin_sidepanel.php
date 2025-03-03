<div class="sidepanel d-lg-block d-flex flex-column flex-shrink-0 p-3 bg-light border-end border-2 position-fixed" style="width: 220px; height: calc(100% - 67.7px)">
  <ul class="nav nav-pills flex-column mb-auto">
    <li>
      <a href="./dashboard" class="nav-link <?php echo getCurrentPage() == 'dashboard.php' ? 'active' : 'link-dark'; ?>" aria-current="page">
        <i class='bx bxs-dashboard me-2' ></i>
        Dashboard
      </a>
    </li>
    <li>
      <a href="./users" class="nav-link <?php echo getCurrentPage() == 'users.php' ? 'active' : 'link-dark'; ?>">
        <i class='bx bx-user me-2'></i>
        Users
      </a>
    </li>
    <li>
      <a href="./campus" class="nav-link <?php echo getCurrentPage() == 'campus.php' ? 'active' : 'link-dark'; ?>">
        <i class='bx bx-buildings me-2' ></i>
        Campuses
      </a>
    </li>
    <li>
      <a href="./appointment" class="nav-link <?php echo getCurrentPage() == 'appointment.php' ? 'active' : 'link-dark'; ?>">
        <i class='bx bx-calendar me-2' ></i>
        Appointment
      </a>
    </li>
    <li>
      <a href="./analytics" class="nav-link <?php echo getCurrentPage() == 'analytics.php' ? 'active' : 'link-dark'; ?>">
        <i class='bx bx-chart me-2' ></i>
        Analytics & Reports
      </a>
    </li>
    <!-- <li>
      <a href="./userPage" class="nav-link <?php echo getCurrentPage() == 'userPage.php' ? 'active' : 'link-dark'; ?>">
        <i class='bx bx-user-circle me-2'></i>
        User Page
      </a>
    </li> -->
    <!-- <li>
      <a href="./staffs" class="nav-link <?php echo getCurrentPage() == 'staffs.php' ? 'active' : 'link-dark'; ?>">
        <i class='bx bx-group me-2' ></i>
        Staff
      </a>
    </li> -->
    <li>
      <a href="./privacyPolicy" class="nav-link <?php echo getCurrentPage() == 'settings.php' ? 'active' : 'link-dark'; ?>">
        <i class='bx bx-cog me-2' ></i>
        Settings
      </a>
    </li>
  </ul>
</div>
