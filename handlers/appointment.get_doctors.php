<?php
require_once('../classes/account.class.php');
require_once('../classes/appointment.class.php');

$appointment = new Appointment();
$account = new Account();

$doctorArray = $account->get_doctor();

?>
<option value="" disabled <?= !isset($_POST['doctor_id']) ? 'selected' : ''
                          ?>>Select Doctor</option>
<?php
foreach ($doctorArray as $item) {

  $appArray = $appointment->get_full_dates($item['doctor_id'], $item['start_wt'], $item['end_wt']);

  $fullDates = array_map(function ($date) {
    return date('Y-m-d', strtotime($date['appointment_date']));
  }, $appArray);

  $formattedDates = implode(', ', $fullDates);
?>
  <option
    data-startday="<?= $item['start_day'] ?>"
    data-endday="<?= $item['end_day'] ?>"
    data-starttime="<?= $item['start_wt'] ?>"
    data-endtime="<?= $item['end_wt'] ?>"
    data-fulldates="<?= $formattedDates ?>"
    value="<?= $item['account_id'] ?>" <?= (isset($_POST['doctor_id']) && $_POST['doctor_id'] ==  $item['account_id']) ? 'selected' : '' ?>>
    <?= $item['doctor_name'] ?>
  </option>
<?php
}

// old stylee heheheheee
// try {
//   $doctors = $account->get_doctor();

//   header('Content-Type: application/json');
//   if ($doctors) {
//     echo json_encode($doctors);
//   } else {
//     echo json_encode([]);F
//   }
// } catch (PDOException $e) {
//   echo json_encode(['error' => $e->getMessage()]);
// }
