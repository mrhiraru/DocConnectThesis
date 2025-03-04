<?php
require_once('../classes/medical_condition.class.php');

$medcon = new MedCon();

if (isset($_POST['add'])) {

    if (isset($_POST['medcon'])) {
        foreach ($_POST['medcon'] as $key => $med) {
            if (!$medcon->is_medcon_exist($med)) {
                $medcon->medcon_name = $med;
                $medcon->add_medcon();
            }
        }
    }
}
