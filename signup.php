<?php
require "DataBase.php";
$db = new DataBase();
if (isset($_POST['FirstName']) && isset($_POST['LastName']) && isset($_POST['DomID']) && isset($_POST['Address']) && isset($_POST['City']) && isset($_POST['PhoneNumber']) && isset($_POST['Email']) && isset($_POST['Password'])) {
    if ($db->dbConnect()) {
        if ($db->signUp("app_users", $_POST['FirstName'], $_POST['LastName'], $_POST['DomID'], $_POST['Address'], $_POST['City'], $_POST['PhoneNumber'], $_POST['Email'], $_POST['Password'])) {
            echo "Sign Up Success";
        } else echo "Sign up Failed";
    } else echo "Error: Database connection";
} else echo "All fields are required";
?>
