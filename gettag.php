<?php
require "test.php";
$db = new DataBase();
$userid = $_GET['user_id'];

if (isset($userid)) {
    if ($db->dbConnect()) {
        if ($db->getVehicleId('vehicles', $_GET['user_id'])) {
            echo "Fetched Data";
        } else echo "Fetching Data Failed";
    } else echo "Error: Database connection";
} else echo "All fields are required!";
?>