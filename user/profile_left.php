<div class="col-lg-3">
  <div class="card bg-body-tertiary mb-4">
    <div class="card-body text-center">
      <img src="../assets/images/66f5b7cd6432c4.31527220.jpg" alt="avatar" class="rounded-3 shadow-lg img-fluid" style="width: 150px;">
      <h4 class="my-3 text-green">Fname Lname</h4>
      <hr>
      <div class="details">
        <div class="d-flex mx-5 mx-md-4">
          <p class="text-muted text-start w-75">Gender</p>
          <div class="text-start w-25">
            <p class="text-black">Male</p>
          </div>
        </div>
        <div class="d-flex mx-5 mx-md-4">
          <p class="text-muted text-start w-75">Age</p>
          <div class="text-start w-25">
            <p class="text-black">21</p>
          </div>
        </div>
        <div class="d-flex mx-5 mx-md-4">
          <p class="text-muted text-start w-75">Height</p>
          <div class="text-start w-25">
            <p class="text-black">171cm</p>
          </div>
        </div>
        <div class="d-flex mx-5 mx-md-4">
          <p class="text-muted text-start w-75">Weight</p>
          <div class="text-start w-25">
            <p class="text-black">157kg</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <div class="card bg-body-tertiary mb-4">
    <div class="card-body text-start">
      <div class="d-flex justify-content-between">
        <h4 class="text-muted mb-2">Allergies</h4>
        <div class="dropdown">
          <i class='bx bx-dots-horizontal-rounded fs-4' id="dotsDropdown" data-bs-toggle="dropdown" aria-expanded="false"></i>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dotsDropdown">
            <li><a class="dropdown-item" href="profile_allergies">Edit</a></li>
          </ul>
        </div>
      </div>

      <?php
        $allergies = [
          ['name' => 'Penicillin', 'level' => 'High'],
          ['name' => 'Dust', 'level' => 'Medium'],
          ['name' => 'Pollen', 'level' => 'Low'],
          ['name' => 'Cat Fur', 'level' => 'Medium']
        ];

        foreach ($allergies as $allergy) {
          $levelClass = '';
          switch (strtolower($allergy['level'])) {
            case 'high':
              $levelClass = 'text-danger';
              break;
            case 'medium':
              $levelClass = 'text-warning';
              break;
            case 'low':
              $levelClass = 'text-success';
              break;
            default:
              $levelClass = 'text-secondary';
              break;
          }
      ?>

      <div class="d-flex justify-content-between mx-4 mx-md-3">
        <p class="text-muted text-start w-50"><?php echo $allergy['name']; ?></p>
        <div class="text-start w-50">
          <p class="<?php echo $levelClass; ?>"><?php echo $allergy['level']; ?></p>
        </div>
      </div>

      <?php 
        }
      ?>
    </div>
  </div>
  
  <div class="card bg-body-tertiary mb-4 mb-lg-0">
    <div class="card-body p-3">
      <h4 class="text-muted mb-2">Notes</h4>
      <textarea class="form-control" id="notes" rows="3" disabled readonly>Not editable by user?</textarea>
    </div>
  </div>
</div>