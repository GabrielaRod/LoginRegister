<?php
require "test.php";
$db = new DataBase();
$data = $_POST['data'];

if (isset($data)) {
    if ($db->dbConnect()) {
        if ($db->test($_POST['data'])) {
            echo "Data Retrieved";
        } else echo " ";
    } else echo "Error: Database connection";
} else echo "All fields are required!";
?>