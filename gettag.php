<?php
require "DataBase.php";
$db = new DataBase();
if (isset($_POST['id'])) {
    if ($db->dbConnect()) {
        if ($db->getTagId("vehicles", $_POST['id'])) {
            echo "Tags found";
        } else echo "Wrong user id";
    } else echo "Error: Database connection";
} else echo "All fields are required";
?>
