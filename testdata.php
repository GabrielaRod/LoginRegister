<?php
require "test.php";
$db = new DataBase();
$Data = $_POST['Data'];
$AntennaId = $_POST['AntennaId'];

if (isset($Data)&& isset($AntennaId)) {
    if ($db->dbConnect()) {
        if ($db->livefeed($_POST['Data'], $_POST['AntennaId'])) {
            echo "Data Retrieved";
        } else echo " ";
    } else echo "Error: Database connection";
} else echo "All fields are required!";
?>