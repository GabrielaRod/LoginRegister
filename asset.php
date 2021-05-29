<?php
require "test.php";
$db = new DataBase();
$VIN = $_GET['VIN'];
$Make = $_GET['Make'];
$Model = $_GET['Model'];
$Year = $_GET['Year'];
$Color = $_GET['Color'];
$Type = $_GET['Type'];
$Tagid = $_GET['Tag'];
$Email = $_GET['Email'];

if (isset($VIN) && isset($Make) && isset($Model) && isset($Year) && isset($Color) && isset($Type) && isset($Tagid) && isset($Email)) {
    if ($db->dbConnect()) {
        if ($db->tagRegistration("vehicles", $_GET['VIN'], $_GET['Make'], $_GET['Model'], $_GET['Year'], $_GET['Color'], $_GET['Type'], $_GET['Tag'], $_GET['Email'])) {
            echo "Registration Sucessful";
        } else echo "Registration Failed";
    } else echo "Error: Database connection";
} else echo "All fields are required!";
?>
