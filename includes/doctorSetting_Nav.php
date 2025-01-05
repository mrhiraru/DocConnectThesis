<div class="row">
  <div class="col">
    <nav aria-label="breadcrumb" class="bg-body-tertiary border-1 border rounded-3 p-3 mb-4">
      <ol class="breadcrumb mb-0">
        <?php
        $current_page = basename($_SERVER['PHP_SELF']);

        $breadcrumbs = [
          'Profile Settings' => 'settings_profile.php',
          'Appointment Settings' => 'settings_appointment.php',
          // 'Privacy and Security' => 'settings_privacy.php',
          // 'Notification Settings' => 'settings_notification.php',
          // 'Patient Interaction Settings' => 'settings_interaction.php'
        ];

        foreach ($breadcrumbs as $name => $url) {
          $isActive = ($current_page == $url) ? 'active' : '';
          
          if ($isActive) {
            echo "<li class='breadcrumb-item text-success $isActive' aria-current='page'>$name</li>";
          } else {
            echo "<li class='breadcrumb-item'><a href='./$url' class='text-dark'>$name</a></li>";
          }
        }
        ?>
      </ol>
    </nav>
  </div>
</div>
