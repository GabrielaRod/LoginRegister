<?php
require "test.php";
$db = new DataBase();
$data = $_GET['data'];

if (isset($data)) {
    if ($db->dbConnect()) {
        if ($db->test($_GET['data'])) {
            echo "Data Retrieved";
        } else echo " ";
    } else echo "Error: Database connection";
} else echo "All fields are required!";
?>