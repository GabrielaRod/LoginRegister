<?php
require "DataBase.php";
$db = new DataBase();
$VehicleId = $_GET['VehicleId'];

if (isset($VehicleId)) {
    if ($db->dbConnect()) {
        if ($db->getVehicleInfo('vehicles', $_GET['VehicleId'])) {
            echo "Data Retrieved";
        } else echo "Fetching Data Failed";
    } else echo "Error: Database connection";
} else echo "All fields are required!";
?>