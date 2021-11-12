<?php
require "DataBase.php";
$db = new DataBase();
$FirstName = $_POST['FirstName'];
$LastName = $_POST['LastName'];
$DomID = $_POST['DomID'];
$Address = $_POST['Address'];
$City = $_POST['City'];
$PhoneNumber = $_POST['PhoneNumber'];
$Email = $_POST['Email'];
$Password = $_POST['Password'];
$Token = $_POST['Token'];

if (isset($FirstName) && isset($LastName) && isset($DomID) && isset($Address) && isset($City) && isset($PhoneNumber) && isset($Email) && isset($Password) && isset($Token)) {
    if ($db->dbConnect()) {
        if ($db->signUp("app_users", $_POST['FirstName'], $_POST['LastName'], $_POST['DomID'], $_POST['Address'], $_POST['City'], $_POST['PhoneNumber'], $_POST['Email'], $_POST['Password'], $_POST['Token'])) {
            echo "Sign Up Success";
        } else echo "Sign up Failed";
    } else echo "Error: Database connection";
} else echo "All fields are required";
?>
