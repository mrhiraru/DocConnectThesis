<?php
require_once('../classes/medical_condition.class.php');
$medcon = new MedCon();

$medconArray = $medcon->show_conditions();
?>
<!-- <option value="" disabled <?php //!isset($_POST['medcon']) ? 'selected' : '' 
                                ?>>Select Diagnosis</option> -->
<?php
foreach ($medconArray as $item) {
?>
    <option value="<?= $item['medcon_name'] ?>" <?= (isset($_POST['medcon']) && $_POST['medcon'] == $item['medcon_name']) ? 'selected' : '' ?>><?= $item['medcon_name'] ?></option>
<?php
}
?>