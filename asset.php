<?php
require "DataBase.php";
$db = new DataBase();
$LicensePlate = $_POST['LicensePlate'];
$VIN = $_POST['VIN'];
$Make = $_POST['Make'];
$Model = $_POST['Model'];
$Year = $_POST['Year'];
$Color = $_POST['Color'];
$Type = $_POST['Type'];
$Tagid = $_POST['Tag'];
$Email = $_POST['Email'];

if (isset($LicensePlate) && isset($VIN) && isset($Make) && isset($Model) && isset($Year) && isset($Color) && isset($Type) && isset($Tagid) && isset($Email)) {
    if ($db->dbConnect()) {
        if ($db->tagRegistration("vehicles", $_POST['LicensePlate'], $_POST['VIN'], $_POST['Make'], $_POST['Model'], $_POST['Year'], $_POST['Color'], $_POST['Type'], $_POST['Tag'], $_POST['Email'])) {
            echo "Registration Successful";
        } else echo "";
    } else echo "Error: Database connection";
} else echo "All fields are required!";
?>
