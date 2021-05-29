<?php
require "test.php";
$db = new DataBase();
$Tagid = $_GET['Tag'];
$VehicleId = $_GET['VehicleId'];

if (isset($Tagid) && isset($VehicleId)) {
    if ($db->dbConnect()) {
        if ($db->setTagId("tags", $_GET['Tag'], $_GET['VehicleId'])) {
            echo "Registration Sucessful";
        } else echo "Registration Failed";
    } else echo "Error: Database connection";
} else echo "All fields are required!";
?>