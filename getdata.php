<?php
require "test.php";
$db = new DataBase();
$Json = $_GET['Json'];
$AntennaId = $_GET['AntennaId'];

if (isset($Json) && isset($AntennaId)) {
    if ($db->dbConnect()) {
        if ($db->getData('locations', $_GET['Json'], $_GET['AntennaId'])) {
            echo "Data Inserted";
        } else echo "ERROR";
    } else echo "Error: Database connection";
} else echo "All fields are required!";
?>