<?php
require "test.php";
$db = new DataBase();
$Email = $_GET['Email'];

if (isset($Email)) {
    if ($db->dbConnect()) {
        if ($db->getUserId('users', $_GET['Email'])) {
            //echo "Data Retrieved";
        } else echo "Fetching Data Failed";
    } else echo "Error: Database connection";
} else echo "All fields are required!";
?>