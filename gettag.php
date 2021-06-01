<?php
require "testing.php";
$db = new DataBase();
$Email = $_GET['Email'];

if (isset($Email)) {
    if ($db->dbConnect()) {
        if ($db->getTagId('tags', $_GET['Email'])) {
            //echo "Data Retrieved";
        } else echo " ";
    } else echo "Error: Database connection";
} else echo "All fields are required!";
?>