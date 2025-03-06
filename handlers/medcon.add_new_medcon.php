<?php
require_once('../classes/medical_condition.class.php');

$medcon = new MedCon();
$medconArray = $medcon->show_conditions(); // Fetch existing conditions

if (isset($_POST['add'])) {
    if (isset($_POST['medcon'])) {
        $medArray = $_POST['medcon'];

        foreach ($medArray as $med) {
            // Check if the condition already exists in the database
            if (!in_array($med, $medconArray)) {
                $medcon->medcon_name = $med;
                $medcon->add_medcon(); // Add new condition
            }
        }
    }
}

// $medcon = new MedCon();

// $medconArray = $medcon->show_conditions();

// if (isset($_POST['add'])) {
//     if (isset($_POST['medcon'])) {
//         $medArray = $_POST['medcon'];
//         foreach ($_POST['medcon'] as $key => $med) {
//             if (!$medcon->is_medcon_exist($med)) {
//                 $medcon->medcon_name = $med;
//                 $medcon->add_medcon();
//             }
//         }
//     }
// }
