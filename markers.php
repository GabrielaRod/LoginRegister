<?php
require "DataBase.php";
$db = new DataBase();
$Email = $_POST['Email'];

if (isset($Email)) {
    if ($db->dbConnect()) {
        if ($db->viewMap("locations", $_POST['Email'])) {
            echo "";
        } else echo "<br> No Markers Found";
    } else echo "Error: Database connection";
} else echo "All fields are required!";
?>