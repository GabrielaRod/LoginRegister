<?php
require "DataBase.php";
$db = new DataBase();
$Email = $_POST['Email'];
$VIN = $_POST['VIN'];

if (isset($Email)) {
    if ($db->dbConnect()) {
        if ($db->disableAlert("reports", $_POST['Email'], $_POST['VIN'])) {
            echo "Alert Disabled Successful";
        } else echo "Alert Disabled Failed";
    } else echo "Error: Database connection";
} else echo "All fields are required!";
?>