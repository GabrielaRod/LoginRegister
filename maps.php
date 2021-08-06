<?php
require "DataBase.php"; //I DONT KNOW IF THIS IS IN USE?
$db = new DataBase();
if (isset($_POST['Latitude']) && isset($_POST['Longitude'])) {
    if ($db->dbConnect()) {
        if ($db->logIn("locations", $_POST['Email'], $_POST['Password'])) {
            echo "Login Success";
        } else echo "Wrong Email or Password";
    } else echo "Error: Database connection";
} else echo "All fields are required";
?>
