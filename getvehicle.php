<?php
require "test.php";
$db = new DataBase();
$userid = $_POST['user_id'];

if (isset($userid)) {
    if ($db->dbConnect()) {
        if ($db->getVehicleId('vehicles', $_POST['user_id'])) {
            echo "Data Retrieved";
        } else echo "Fetching Data Failed";
    } else echo "Error: Database connection";
} else echo "All fields are required!";
?>