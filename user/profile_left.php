<?php
if (isset($_SESSION['fullname']) && !empty($_SESSION['fullname'])) {
  $nameParts = explode(" ", trim($_SESSION['fullname']));

  $firstName = $nameParts[0];
  $lastName = end($nameParts);

  $displayName = $firstName . " " . $lastName;
} else {
  $displayName = "N/A";
}
?>

<div class="col-lg-3">
  <div class="card bg-body-tertiary mb-4">
    <div class="card-body text-center">
      <div class="rounded-3 shadow-lg overflow-hidden mx-auto" style="width: 150px; height: 150px;">
        <img src="<?php if (isset($_SESSION['account_image'])) {
                    echo "../assets/images/" . $_SESSION['account_image'];
                  } else {
                    echo "../assets/images/default_profile.png";
                  } ?>" alt="avatar" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
      </div>
      <h4 class="my-3 text-green"><?= $displayName ?></h4>
      <hr>
      <div class="details">
        <div class="d-flex mx-5 mx-md-4">
          <p class="text-muted text-start w-75">Gender</p>
          <div class="text-start w-50">
            <p class="text-black"><?= $_SESSION['gender'] ?></p>
          </div>
        </div>
        <?php
        if (!empty($birthdate)) {
          $birthDateObj = new DateTime($birthdate);
          $today = new DateTime();
          $_SESSION['age'] = $birthDateObj->diff($today)->y;
        } else {
          $_SESSION['age'] = "N/A";
        }
        ?>
        <div class="d-flex mx-5 mx-md-4">
          <p class="text-muted text-start w-75">Age</p>
          <div class="text-start w-50">
            <p class="text-black"><?= isset($_SESSION['age']) ? $_SESSION['age'] : "N/A" ?></p>
          </div>
        </div>
        <div class="d-flex mx-5 mx-md-4">
          <p class="text-muted text-start w-75">Height</p>
          <div class="text-start w-50">
            <p class="text-black"><?= isset($_SESSION['height']) ? $_SESSION['height'] . " cm" : "N/A" ?></p>
          </div>
        </div>
        <div class="d-flex mx-5 mx-md-4">
          <p class="text-muted text-start w-75">Weight</p>
          <div class="text-start w-50">
            <p class="text-black"><?= isset($_SESSION['weight']) ? $_SESSION['weight'] . " kg" : "N/A" ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- <div class="card bg-body-tertiary mb-4 mb-lg-0">
    <div class="card-body p-3">
      <h4 class="text-muted mb-2">Notes</h4>
      <textarea class="form-control" id="notes" rows="3" disabled readonly>Not editable by user?</textarea>
    </div>
  </div> -->
</div>