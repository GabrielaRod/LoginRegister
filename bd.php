<?php
require "test.php";
$db = new DataBase();
$Json = $_POST['data'];
$AntennaId = $_POST['location_id'];

if (isset($Json) && isset($AntennaId)) {
    if ($db->dbConnect()) {
        if ($db->livefeed('test', $_POST['data'], $_POST['location_id'])) {
            echo "Data Inserted";
        } else echo "ERROR";
    } else echo "Error: Database connection";
} else echo "All fields are required!";
?>