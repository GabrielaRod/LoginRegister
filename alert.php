<?php
require "DataBase.php";
$db = new DataBase();
$VIN = $_POST['VIN'];
$Make = $_POST['Make'];
$Model = $_POST['Model'];
$Color = $_POST['Color'];
$Status = $_POST['Status'];
$Email = $_POST['Email'];

if (isset($VIN) && isset($Make) && isset($Model) && isset($Color) && isset($Status) && isset($Email)) {
    if ($db->dbConnect()) {
        if ($db->createAlert("reports", $_POST['VIN'], $_POST['Make'], $_POST['Model'], $_POST['Color'], $_POST['Status'], $_POST['Email'])) {
            echo "Registration Successful";
        } else echo "Registration Failed";
    } else echo "Error: Database connection";
} else echo "All fields are required!";
?>