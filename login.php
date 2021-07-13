<?php
require "DataBase.php";
$db = new DataBase();
if (isset($_POST['Email']) && isset($_POST['Password'])) {
    if ($db->dbConnect()) {
        if ($db->logIn("app_users", $_POST['Email'], $_POST['Password'])) {
            echo "Login Success";
        } else echo "Wrong Email or Password";
    } else echo "Error: Database connection";
} else echo "All fields are required";
?>
