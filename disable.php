<?php
require "DataBase.php";
$db = new DataBase();
$Email = $_GET['Email'];

if (isset($Email)) {
    if ($db->dbConnect()) {
        if ($db->disableAlert("reports", $_GET['Email'])) {
            echo "Alert Disabled Successful";
        } else echo "Alert Disabled Failed";
    } else echo "Error: Database connection";
} else echo "All fields are required!";
?>