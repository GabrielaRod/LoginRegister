<?php
require "DataBase.php";
$db = new DataBase();
if (isset($_POST['VIN']) && isset($_POST['Make']) && isset($_POST['Model']) && isset($_POST['Year']) && isset($_POST['Color']) && isset($_POST['Type']) && isset($_POST['user_id'])) {
    if ($db->dbConnect()) {
        if ($db->assetRegistration("vehicles", $_POST['VIN'], $_POST['Make'], $_POST['Model'], $_POST['Year'], $_POST['Color'], $_POST['Type'], $_POST['user_id']) {
            echo "Registration Sucessful";
        } else echo "Registration Failed";
    } else echo "Error: Database connection";
} else echo "All fields are required";
?>
