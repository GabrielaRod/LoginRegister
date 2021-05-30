<?php
require "test.php";
$db = new DataBase();
$Tagid = $_POST['Tag'];
$VehicleId = $_POST['VehicleId'];

if (isset($Tagid) && isset($VehicleId)) {
    if ($db->dbConnect()) {
        if ($db->setTagId("tags", $_POST['Tag'], $_POST['VehicleId'])) {
            echo "Registration Sucessful";
        } else echo "Registration Failed";
    } else echo "Error: Database connection";
} else echo "All fields are required!";
?>