<?php
require "DataBase.php";
$db = new DataBase();
$Email = $_POST['Email'];

if (isset($Email)) {
    if ($db->dbConnect()) {
        if ($db->getAlert('reports', $_POST['Email'])) {
            echo "Data Retrieved";
        } else echo " ";
    } else echo "Error: Database connection";
} else echo "All fields are required!";
?>