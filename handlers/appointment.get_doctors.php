<?php
require_once('../classes/account.class.php');
$account = new Account();

$doctorArray = $account->get_doctor();

?>
<option value="" disabled <?= !isset($_POST['doctor_id']) ? 'selected' : ''
                          ?>>Select Doctor</option>
<?php
foreach ($doctorArray as $item) {
?>
  <option
    data-startday="<?= $item['start_day'] ?>"
    data-endday="<?= $item['end_day'] ?>"
    data-starttime="<?= $item['start_wt'] ?>"
    data-endtime="<?= $item['end_wt'] ?>"
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
