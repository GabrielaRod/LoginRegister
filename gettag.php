<?php
require "DataBase.php";
$db = new DataBase();
$VehicleId = $_POST['vehicle_id'];

if (isset($VehicleId)) {
    if ($db->dbConnect()) {
        if ($db->getTagId('tags', $_POST['vehicle_id'])) {
            //echo "Data Retrieved";
        } else echo "Fetching Data Failed";
    } else echo "Error: Database connection";
} else echo "All fields are required!";
?>