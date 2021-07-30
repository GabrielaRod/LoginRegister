<?php
require "test.php";
$db = new DataBase();
$Email = $_POST['Email'];

if (isset($Email)) {
    if ($db->dbConnect()) {
        if ($db->getUserId('app_users', $_POST['Email'])) {
            //echo "Data Retrieved";
        } else echo "Fetching Data Failed";
    } else echo "Error: Database connection";
} else echo "All fields are required!";
?>