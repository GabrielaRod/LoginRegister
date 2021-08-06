<?php
require "DataBase.php";
$db = new DataBase();
$Email = $_GET['Email'];

if (isset($Email)) {
    if ($db->dbConnect()) {
        if ($db->viewMap("locations", $_GET['Email'])) {
            echo "<br> Markers Fetch Sucessfuly";
        } else echo "<br> No Markers Found";
    } else echo "Error: Database connection";
} else echo "All fields are required!";
?>