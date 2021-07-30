<?php
require "test.php";
$db = new DataBase();
$Json = $_POST['Json'];
$AntennaId = $_POST['AntennaId'];

if (isset($Json) && isset($AntennaId)) {
    if ($db->dbConnect()) {
        if ($db->getData('locations', $_POST['Json'], $_POST['AntennaId'])) {
            echo "Data Inserted";
        } else echo "ERROR";
    } else echo "Error: Database connection";
} else echo "All fields are required!";
?>