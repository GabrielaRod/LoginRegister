<?php
require "DataBase.php";
$db = new DataBase();
$VehicleId = $_POST['VehicleId'];

if (isset($VehicleId)) {
    if ($db->dbConnect()) {
        if ($db->getVehicleInfo('vehicles', $_POST['VehicleId'])) {
            echo "Data Retrieved";
        } else echo "";
    } else echo "Error: Database connection";
} else echo "All fields are required!";
?>