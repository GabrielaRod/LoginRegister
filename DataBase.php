<?php
require "DataBaseConfig.php";

class DataBase
{
    public $connect;
    public $data;
    private $sql;
    protected $servername;
    protected $username;
    protected $password;
    protected $databasename;

    public function __construct()
    {
        $this->connect = null;
        $this->data = null;
        $this->sql = null;
        $dbc = new DataBaseConfig();
        $this->servername = $dbc->servername;
        $this->username = $dbc->username;
        $this->password = $dbc->password;
        $this->databasename = $dbc->databasename;
    }

    function dbConnect()
    {
        $this->connect = mysqli_connect($this->servername, $this->username, $this->password, $this->databasename);
        return $this->connect;
    }

    function prepareData($data)
    {
        return mysqli_real_escape_string($this->connect, stripslashes(htmlspecialchars($data)));
    }

    function logIn($table, $email, $password)
    {
        $email = $this->prepareData($email);
        $password = $this->prepareData($password);
        $this->sql = "select * from " . $table . " where Email = '" . $email . "'";
        $result = mysqli_query($this->connect, $this->sql);
        $row = mysqli_fetch_assoc($result);
        if (mysqli_num_rows($result) != 0) {
            $dbemail = $row['Email'];
            $dbpassword = $row['Password'];
            if ($dbemail == $email && password_verify($password, $dbpassword)) {
                $login = true;
            } else $login = false;
        } else $login = false;

        return $login;
    }

    function signUp($table, $firstname, $lastname, $domid, $address, $city, $phonenumber, $email, $password)
    {
        $firstname = $this->prepareData($firstname);
        $lastname = $this->prepareData($lastname);
        $domid = $this->prepareData($domid);
        $address = $this->prepareData($address);
        $city = $this->prepareData($city);
        $phonenumber = $this->prepareData($phonenumber);
        $email = $this->prepareData($email);
        $password = password_hash($password, PASSWORD_DEFAULT);
        $this->sql =
            "INSERT INTO " . $table . " (FirstName, LastName, DomID, Address, City, PhoneNumber, Email, Password) VALUES ('" . $firstname . "','" . $lastname . "','" . $domid . "','" . $address . "','" . $city . "','" . $phonenumber . "','" . $email . "','" . $password . "')";
        if (mysqli_query($this->connect, $this->sql)) {
            return true;
        } else return false;
    }


     function tagRegistration($tagid, $vehicleid)
    {
        $tagid = $this->prepareData($tagid);
        $vehicleid = $this->prepareData($vehicleid);

        $this->sql =
            "INSERT INTO " . Tags . " (Tag, vehicle_id) VALUES ('" . $tagid . "','" . $vehicleid . "')";
        if (mysqli_query($this->connect, $this->sql)) {
            return true;
        } else return false;
    }

    function getUserId($email){

        $email = $this->prepareData($email);
        $userid;

        $this->sql = "Select * from users where Email = '" . $email . "'"; 
        $result = mysqli_query($this->connect, $this->sql);
        $row = mysqli_fetch_assoc($result);
        if (mysqli_num_rows($result) != 0) {
            $dbemail = $row['Email'];
            $dbuser_id = $row['id'];
            if ($dbemail == $email) {
                $userid = $dbuser_id;
            } else return false;
        } else return false;

        return $userid;
    }


    function assetRegistration($table, $vin, $make, $model, $year, $color, $type, $tagid, $email) //TODO Select the user_id to link the vehicle to the login user 
    {
        $vin = $this->prepareData($vin);
        $make = $this->prepareData($make);
        $model = $this->prepareData($model);
        $year = $this->prepareData($year);
        $color = $this->prepareData($color);
        $type = $this->prepareData($type);
        $tagid = $this->prepareData($tagid);
        $email = $this->prepareData($email);
        $vehicleid;


        $email = $this->prepareData($email);
        $userid;

        $this->sql = "Select * from users where Email = '" . $email . "'"; 
        $result = mysqli_query($this->connect, $this->sql);
        $row = mysqli_fetch_assoc($result);
        if (mysqli_num_rows($result) != 0) {
            $dbemail = $row['Email'];
            $dbuser_id = $row['id'];
            if ($dbemail == $email) {
                $userid = $dbuser_id;
                echo $userid;
            } else return false;
        } else return false;


        $this->sql =
            "INSERT INTO " . $table . " (VIN, Make, Model, Year, Color, Type, user_id) VALUES ('" . $vin . "','" . $make . "','" . $model . "','" . $year . "','" . $color . "','" . $type . "','" . $userid . "')";

        if (mysqli_query($this->connect, $this->sql)) {
            return true;
        } else return false;

        
        $this->sql = "Select * from vehicles where VIN = '" . $vin . "'"; //Retrieve new stored vehicle to store the Tag into the Tags table.
                $result = mysqli_query($this->connect, $this->sql);
                $row = mysqli_fetch_assoc($result);
            if (mysqli_num_rows($result) != 0) {
                $dbvin = $row['VIN'];
                $dbvehicle_id = $row['id'];
                if ($dbvin == $vin) {
                    $vehicleid = $dbvehicle_id;
                    echo $vehicleid;

                    tagRegistration($tagid, $vehicleid);
                    
                } else return false;
             }else return false;
        }

}

?>