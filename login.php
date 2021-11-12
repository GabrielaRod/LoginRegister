<?php
require "DataBase.php";
$db = new DataBase();
$Email = $_POST['Email'];
$Password = $_POST['Password'];
$Token = $_POST['Token'];

if (isset($Email) && isset($Password) && isset($Token)) {
    if ($db->dbConnect()) {
        if ($db->logIn("app_users", $_POST['Email'], $_POST['Password'], $_POST['Token'])) {
            echo "Login Success";
        } else echo "Wrong Email or Password";
    } else echo "Error: Database connection";
} else echo "All fields are required";
?>
