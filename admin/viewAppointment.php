<html lang="en">
<?php
  $title = 'Campuses | View Appointment';
  include './includes/admin_head.php';
  function getCurrentPage() {
    return basename($_SERVER['PHP_SELF']);
  }
?>
<body>
  <?php
    require_once ('./includes/admin_header.php');
  ?>
  <?php
    require_once ('./includes/admin_sidepanel.php');
  ?>

  <section id="add_campus" class="page-container">
    <div class="row">

      <div class="col-2"></div>

      <div class="col-12 col-lg-8">
        <form>
          <div class="border shadow p-4 mb-5 bg-body rounded">
            <div class="d-flex align-items-center">
              <button class="btn p-0 me-auto" onclick="event.preventDefault(); window.history.back();">
                <i class='bx bx-chevron-left-circle fs-3 link'></i>
              </button>
              <?php
                $status = "completed";
                $statusClass = "";
                switch (strtolower($status)) {
                  case "completed":
                    $statusClass = "text-success";
                    break;
                  case "in progress":
                    $statusClass = "text-info";
                    break;
                  case "cancelled":
                    $statusClass = "text-danger";
                    break;
                  case "waiting":
                    $statusClass = "text-warning";
                    break;
                  default:
                    $statusClass = "text-secondary";
                    break;
                }
              ?>
              <h5 class="text-center w-100 mb-0">Status: <span class="<?php echo $statusClass; ?>"><?php echo ucfirst($status); ?></span></h5>
            </div>

            <hr class="mx-3 my-4">

            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3">
              <div class="col d-flex ">
                <strong class="me-2">Code:</strong>
                <p>0001</p>
              </div>

              <div class="col d-flex ">
                <strong class="me-2">Type:</strong>
                <p>Face to face</p>
              </div>

              <div class="col d-flex ">
                <strong class="me-2">Date:</strong>
                <p>Monday, 9:00am - 10:00pm</p>
              </div>

              <div class="col d-flex ">
                <strong class="me-2">Campus:</strong>
                <p>Campus B</p>
              </div>

              <div class="col d-flex ">
                <strong class="me-2">Address:</strong>
                <p>Zambnonaga City</p>
              </div>

              <div class="col d-flex ">
                <strong class="me-2">Patient:</strong>
                <p>Allen Barry</p>
              </div>

              <div class="col d-flex ">
                <strong class="me-2">Email:</strong>
                <p>username123@email.com</p>
              </div>

              <div class="col d-flex ">
                <strong class="me-2">phone:</strong>
                <p>+63 9xx xxx xxxx</p>
              </div>
            </div>

            <div class="d-flex flex-column justify-content-end mb-3">
              <label for="reasons" class="form-label"><strong>Reason of Visit/Appointment</strong></label>
              <textarea class="form-control" id="reason" rows="3" disabled readonly>Masakit ulo</textarea>
            </div>

            <div class="col d-flex ">
                <strong class="me-2">Doctor:</strong>
                <p class="mb-0">Dr. Thomas Wayne</p>
              </div>

            <div class="d-flex flex-column justify-content-end">
              <label for="notes" class="form-label"><strong>Notes</strong></label>
              <textarea class="form-control" id="notes" rows="3" disabled readonly>Masakit ulo</textarea>
            </div>            

          </div>
        </form>

      </div>

      <div class="col-2"></div>

    </div>    
  </section>
</body>
</html>
