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

    /*************** LOGIN USER ***************/
    function tokenUpdate($table, $token, $email) /*To update FCM Token for the Notifications*/
    {
        $token = $this->prepareData($token);

        $this->sql = "UPDATE " . $table . " SET Token= '" . $token . "'" . " WHERE Email= '" . $email . "'";
        
        if (mysqli_query($this->connect, $this->sql)) {
            return true;
        } else return false;
    }

    function logIn($table, $email, $password, $token)
    {
        $email = $this->prepareData($email);
        $password = $this->prepareData($password);
        $token = $this->prepareData($token);

        $this->sql = "select * from " . $table . " where Email = '" . $email . "'";
        $result = mysqli_query($this->connect, $this->sql);
        $row = mysqli_fetch_assoc($result);
        if (mysqli_num_rows($result) != 0) {
            $dbemail = $row['Email'];
            $dbpassword = $row['Password'];
            if ($dbemail == $email && password_verify($password, $dbpassword)) {
                $this->tokenUpdate($table, $token, $email);
                $login = true;
            } else $login = false;
        } else $login = false;

        return $login;
    }

    /*************** SIGN UP USER ***************/
    function signUp($table, $firstname, $lastname, $domid, $address, $city, $phonenumber, $email, $password, $token)
    {
        $firstname = $this->prepareData($firstname);
        $lastname = $this->prepareData($lastname);
        $domid = $this->prepareData($domid);
        $address = $this->prepareData($address);
        $city = $this->prepareData($city);
        $phonenumber = $this->prepareData($phonenumber);
        $email = $this->prepareData($email);
        $password = password_hash($password, PASSWORD_DEFAULT);
        $token = $this->prepareData($token);

        $this->sql =
            "INSERT INTO " . $table . " (FirstName, LastName, DomID, Address, City, PhoneNumber, Email, Password, Token) VALUES ('" . $firstname . "','" . $lastname . "','" . $domid . "','" . $address . "','" . $city . "','" . $phonenumber . "','" . $email . "','" . $password . "','" . $token . "')";
        if (mysqli_query($this->connect, $this->sql)) {
            return true;
        } else return false;
    }


    /*************** GET USER ID ***************/
    function getUserId($table, $email){

        $email = $this->prepareData($email);
        $userid;

        $this->sql = "select * from " . $table . " where Email = '" . $email . "'";
        $result = mysqli_query($this->connect, $this->sql);
        $row = mysqli_fetch_assoc($result);
        if (mysqli_num_rows($result) != 0) {
            $dbemail = $row['Email'];
            $dbuser_id = $row['id'];
            if ($dbemail == $email) {
                $userid = $dbuser_id;
                return $userid;                    
            } 
            else return false;
        } 
        else return false;
    }

    /*************** GET USER INFORMATION (CREATE REPORT ENTRY) ***************/
    function getUserInfo($table, $email){

        $email = $this->prepareData($email);

        $this->sql = "select * from " . $table . " where Email = '" . $email . "'";

        $result = mysqli_query($this->connect, $this->sql);
        $row = mysqli_fetch_assoc($result);

        if (mysqli_num_rows($result) != 0) {
            $dbemail = $row['Email'];

            if ($dbemail == $email) {
               $return_arr['user'] = array();
                array_push($return_arr['user'], array(
                            'FirstName'=>$row['FirstName'],
                            'LastName'=>$row['LastName']
                        ));
                        return json_encode($return_arr);              
            } 
            else return false;
        } 
        else return false;
    }


    /*************** GET VEHICLE INFORMATION (CREATE REPORT ENTRY) ***************/
    function getVehicleInfo($table, $vehicleid){

        $vehicleid = $this->prepareData($vehicleid);

        $this->sql = "select * from " . $table . " where id = '" . $vehicleid . "'";
        
        $result = mysqli_query($this->connect, $this->sql);
        $row = mysqli_fetch_assoc($result);

        if (mysqli_num_rows($result) != 0) {
            $dbid = $row['id'];

            if ($dbid == $vehicleid) {
                $return_arr = array();
                array_push($return_arr, array(
                            'LicensePlate'=>$row['LicensePlate'],
                            'VIN'=>$row['VIN'],
                            'Make'=>$row['Make'],
                            'Model'=>$row['Model'],
                            'Color'=>$row['Color']
                        ));
                        echo json_encode($return_arr); 
                        return true; 
                    }                                     
                
                else return false;
            } 
            else return false;
    }

    
    /*************** GET VEHICLE ID ***************/
    function getVehicleId($vin){ //Retrieves all vehicle data related to the tags registered by the user

        $vin = $this->prepareData($vin);


        $this->sql = "Select * from vehicles where VIN = '" . $vin . "'"; //Retrieve new stored vehicle to store the Tag into the Tags table.
        
        $result = mysqli_query($this->connect, $this->sql);
        $row = mysqli_fetch_assoc($result);

        if (mysqli_num_rows($result) != 0) {
                $dbvin_id = $row['VIN'];    

            if ($dbvin_id == $vin) {
                $return_arr['vehicles'] = array();
                     array_push($return_arr['vehicles'], array(
                            'Vehicle_Id'=>$row['id'],
                            'VIN'=>$row['VIN']
                        ));
                    while($row = mysqli_fetch_assoc($result)){
                        array_push($return_arr['vehicles'], array(
                            'Vehicle_Id'=>$row['id'],
                            'VIN'=>$row['VIN']
                        ));
                    }
                    return json_encode($return_arr);
                }
                else echo 'Error1';
            }
             else echo 'Error2'; 
        }


    /*************** SET TAG ID ***************/
    function setTagId($tagid, $vehicleid){

        $tagid = $this->prepareData($tagid);
        $vehicleid = $this->prepareData($vehicleid);

         $this->sql = "INSERT INTO tags (Tag, vehicle_id) VALUES ('" . $tagid . "','" . $vehicleid . "')";
            if (mysqli_query($this->connect, $this->sql)) {
                echo 'Registration Successful';
                return true;
            } else return false; 
        
    }

    /*************** GET VEHICLE ID (THIS FUNCTION IS ONLY USED BY getTagId)***************/
    /*1. Retrieves all vehicle data related to the tags registered by the user
      2. All info gets inserted in an Array of Json Data*/
     function getVehicle($table, $email){ 

        $email = $this->prepareData($email);

        $userid = $this->getUserId('app_users', $email);


        /*To select vehicle id based on user_id*/
        $this->sql = "SELECT * FROM " . $table . " WHERE app_user_id ='" . $userid . "'";

        $result = mysqli_query($this->connect, $this->sql);
        $row = mysqli_fetch_assoc($result);

        if (mysqli_num_rows($result) != 0) {
                $dbvehicle_id = $row['id'];    
                $vehicleid = $dbvehicle_id;

            if ($dbvehicle_id == $vehicleid) {
                $return_arr['vehicles'] = array();
                     array_push($return_arr['vehicles'], array(
                            'Vehicle_Id'=>$row['id'],
                            'LicensePlate'=>$row['LicensePlate'],
                            'User_Id'=>$row['app_user_id']
                        ));
                    while($row = mysqli_fetch_assoc($result)){
                        array_push($return_arr['vehicles'], array(
                            'Vehicle_Id'=>$row['id'],
                            'LicensePlate'=>$row['LicensePlate'],
                            'User_Id'=>$row['app_user_id']
                        ));
                    }
                    return json_encode($return_arr);
                }
                else echo 'Error1';
            }
             else echo 'Error2'; 
    }

    /*************** GET TAG ID ***************/
    /*1. Gets all info related to vehicle_id provided
      2. All Tag id's get inserted in an array or JSONs*/
    function getTagId($table, $email){

       $email = $this->prepareData($email);

        $response = $this->getVehicle('vehicles', $email);
        $result = json_decode($response, true);

        $return_arr = array();

            foreach ($result['vehicles'] as $element) {
                    $vehicleid = $element['Vehicle_Id']; 
                    $licenseplate = $element['LicensePlate'];


                    /*To select vehicle id's related to the app_user_id*/
                    $this->sql = "SELECT * FROM " . $table . " WHERE vehicle_id ='" . $vehicleid . "'";

                    $result2 = mysqli_query($this->connect, $this->sql);
                    $row = mysqli_fetch_assoc($result2);

                    if (mysqli_num_rows($result2) != 0) {
                        $dbvehicle_id = $row['vehicle_id'];    

                    if ($dbvehicle_id == $vehicleid) {
                        array_push($return_arr, array(
                            'Tag'=>$row['Tag'],
                            'LicensePlate'=>$licenseplate,
                            'Vehicle_Id'=>$row['vehicle_id']
                            ));
                    while($row = mysqli_fetch_assoc($result2)){
                        array_push($return_arr, array(
                            'Tag'=>$row['Tag'],
                            'LicensePlate'=>$licenseplate,
                            'Vehicle_Id'=>$row['vehicle_id']
                            ));
                        }
                    
                    }
                    else echo 'Error1';      
                    }
                else echo '';
                }
        echo json_encode($return_arr);
    }


    /*************** PIVOT TABLE FOR APP_USERS & VEHICLE_ID ***************/
    function userVehicle($userid, $vehicleid)  
        {
            $table = 'app_user_vehicle';
            $userid = $this->prepareData($userid);
            $vehicleid = $this->prepareData($vehicleid);

            $this->sql =
            "INSERT INTO " . $table . " (app_user_id, vehicle_id) VALUES ('" . $userid . "','" . $vehicleid . "')";
        if (mysqli_query($this->connect, $this->sql)) { 
            return true;
        } else return false;       

        }

    /*************** ASSET REGISTRATION ***************/
    function assetRegistration($table, $vin, $make, $model, $year, $color, $type, $tagid, $email)  
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

        $userid = $this->getUserId('app_users', $email);
               

        $this->sql =
         "INSERT INTO " . $table . " (VIN, Make, Model, Year, Color, Type, app_user_id) VALUES ('" . $vin . "','" . $make . "','" . $model . "','" . $year . "','" . $color . "','" . $type . "','" . $userid . "')";
        if (mysqli_query($this->connect, $this->sql)) { 
            return true;
        } else return false;       
        
        $vehicleid = $this->getVehicleId($vin);
        $this->userVehicle($userid, $vehicleid);
        
        }

   /* function assetRegistration($table, $vin, $make, $model, $year, $color, $type, $tagid, $email){

        $vin = $this->prepareData($vin);
        $make = $this->prepareData($make);
        $model = $this->prepareData($model);
        $year = $this->prepareData($year);
        $color = $this->prepareData($color);
        $type = $this->prepareData($type);
        $tagid = $this->prepareData($tagid);
        $email = $this->prepareData($email);
        $vehicleid;

        $userid = $this->getUserId('users', $email);
               

        $this->sql =
            "INSERT INTO " . $table . " (VIN, Make, Model, Year, Color, Type, user_id) VALUES ('" . $vin . "','" . $make . "','" . $model . "','" . $year . "','" . $color . "','" . $type . "','" . $userid . "')";
        if (mysqli_query($this->connect, $this->sql)) { 
            return true;
        } else return false;       
             
        
    }*/

    /*************** REGISTER TAG ***************/
    function tagRegistration($table, $licenseplate, $vin, $make, $model, $year, $color, $type, $tagid, $email){

        $licenseplate = $this->prepareData($licenseplate);
        $vin = $this->prepareData($vin);
        $make = $this->prepareData($make);
        $model = $this->prepareData($model);
        $year = $this->prepareData($year);
        $color = $this->prepareData($color);
        $type = $this->prepareData($type);
        $tagid = $this->prepareData($tagid);
        $email = $this->prepareData($email);

        $this->assetRegistration('vehicles', $licenseplate, $vin, $make, $model, $year, $color, $type, $tagid, $email);

        $response = $this->getVehicleId($vin);
        $result = json_decode($response, true);
            foreach ($result['vehicles'] as $element) {
                    $vehicleid = $element['Vehicle_Id'];
            }

        $this->setTagId($tagid, $vehicleid); 
           
    }

    /*************** CREATE ALERT ***************/
    function createAlert($table, $vin, $licenseplate, $make, $model, $color, $status, $email){

        $vin = $this->prepareData($vin);
        $licenseplate = $this->prepareData($licenseplate);
        $make = $this->prepareData($make);
        $model = $this->prepareData($model);
        $color = $this->prepareData($color);
        $status = $this->prepareData($status);
        $email = $this->prepareData($email);

        $response = $this->getUserInfo('app_users', $email);

        $result = json_decode($response, true);
            foreach($result['user'] as $data){
                $firstname = $data['FirstName'];
                $lastname = $data['LastName'];
            }

        $this->sql =
            "INSERT INTO " . $table . " (VIN, LicensePlate, Make, Model, Color, FirstName, LastName, Email, Status) VALUES ('" . $vin . "','" . $licenseplate . "','" . $make . "','" . $model . "','" . $color . "','" . $firstname . "','" . $lastname . "','" . $email . "','". $status . "')";
        if (mysqli_query($this->connect, $this->sql)) { 
            return true;
        } else return false;     
            
    }


    /*************** GET ALERTS ***************/
    function getAlert($table, $email){

        $email = $this->prepareData($email);

        $this->sql = "SELECT * from " . $table . " WHERE `Status` = 'ACTIVE' AND Email = '" . $email . "'";

        $result = mysqli_query($this->connect, $this->sql);
        $row = mysqli_fetch_assoc($result);

        $return_arr = array();

        if (mysqli_num_rows($result) != 0) {
            $dbemail = $row['Email'];

            if ($dbemail == $email) {
                array_push($return_arr, array(
                            'VIN'=>$row['VIN'],
                            'Make'=>$row['Make'],
                            'Model'=>$row['Model'],
                            'Color'=>$row['Color']
                        ));
                while($row = mysqli_fetch_assoc($result)){
                array_push($return_arr, array(
                            'VIN'=>$row['VIN'],
                            'Make'=>$row['Make'],
                            'Model'=>$row['Model'],
                            'Color'=>$row['Color']
                        ));
                        }
                        echo json_encode($return_arr);  
                    }
                
                else return false;
            } 
            else return false; 
            
    }

    /*************** DISABLE ALERT ***************/
    function disableAlert($table, $email, $vin){
        $email = $this->prepareData($email);
        $vin = $this->prepareData($vin);

        $this->sql = "UPDATE " . $table . " SET `Status` = 'INACTIVE' WHERE `Status` = 'ACTIVE' AND `Email` = '" . $email . "'" . " AND `VIN` = '" . $vin . "'";
        if (mysqli_query($this->connect, $this->sql)) { 
            return true;
        } else return false;     
            
    }


    /*************** GET VIN  ***************/
    function getVIN($table, $email){
        $table = $this->prepareData($table); // Table 'reports'
        $email = $this->prepareData($email);
        $VIN;


        /*To select vehicle VIN based on Email and Status*/
        $this->sql = " SELECT * FROM  " . $table . " WHERE `Status` = 'ACTIVE' AND `Email` = '" . $email . "'";
        
        $result = mysqli_query($this->connect, $this->sql);
        $row = mysqli_fetch_assoc($result);
        if (mysqli_num_rows($result) != 0) {
            $dbemail = $row['Email'];
            $dbVIN = $row['VIN'];
            if ($dbemail == $email) {
                $VIN = $dbVIN;
                return $VIN;                    
            } 
            else return false;
        } 
        else return false;
   
            
    }

      /*************** GET TAG ID  ***************/
    function getTag($table, $vehicleid){
        $table = $this->prepareData($table); // Table 'tags'
        $vehicleid = $this->prepareData($vehicleid);
        $Tag;


        /*To select vehicle VIN based on Email and Status*/
        $this->sql = " SELECT * FROM  " . $table . " WHERE vehicle_id = '" . $vehicleid . "'";
        
        $result = mysqli_query($this->connect, $this->sql);
        $row = mysqli_fetch_assoc($result);
        if (mysqli_num_rows($result) != 0) {
            $dbvehicle_id = $row['vehicle_id'];
            $dbTag = $row['Tag'];
            if ($dbvehicle_id == $vehicleid) {
                $Tag = $dbTag;
                return $Tag;                    
            } 
            else return false;
        } 
        else return false;
   
            
    }

    /*************** SHOW MARKERS ON MAP ***************/
    function viewMap($table, $email){
        $table = $this->prepareData($table);  //Table locations
        $email = $this->prepareData($email);

        $VIN = $this->getVIN('reports',$email);


        $response = $this->getVehicleId($VIN);
        $result = json_decode($response, true);
            foreach ($result['vehicles'] as $element) {
                    $vehicleid = $element['Vehicle_Id'];
            }

        $TagId = $this->getTag('tags', $vehicleid);

        /*To select Latitude and Longitud associated to the TagId provided*/
        $this->sql = " SELECT * FROM  " . $table . " WHERE TagID = '" . $TagId . "'";

        $result = mysqli_query($this->connect, $this->sql);
        $row = mysqli_fetch_assoc($result);

        $return_arr = array();

        if (mysqli_num_rows($result) != 0) {
            $dbTagID = $row['TagID'];

            if ($dbTagID == $TagId) {                
                array_push($return_arr, array(
                            'Latitude'=>$row['Latitude'],
                            'Longitude'=>$row['Longitude']
                        ));
                       while($row = mysqli_fetch_assoc($result)){
                            array_push($return_arr, array(
                            'Latitude'=>$row['Latitude'],
                            'Longitude'=>$row['Longitude']
                        ));
                        }
                        echo json_encode($return_arr);   
                        return true;
                    }         
                
                else return false;
            } 
            else return false;   
            
    }



     /************************************************/
     /* This querys are related to the Raspberry Pi */
     /***********************************************/

    /*************** CHECK IF MACADDRESS ALREADY EXISTS IN LOCATION ***************/
    function ifExists($macAddress)
    {
        $macAddress = $this->prepareData($macAddress);
        
        $this->sql = "SELECT * FROM locations WHERE `created_at` >= NOW() - INTERVAL 5 MINUTE AND `TagID` = '" . $macAddress . "'";
        $result = mysqli_query($this->connect, $this->sql);
        $row = mysqli_fetch_assoc($result);

        if (mysqli_num_rows($result) != 0) {
            $dbmacAddress = $row['TagID'];
            if($dbmacAddress == $macAddress)
            {
                $exists = true;
            }  
            else $exists = false;
            } 
            else $exists = false;

        return $exists;
    }

    function getLocation($locationid)
    {
        $locationid = $this->prepareData($locationid);
        
        $this->sql = "SELECT * FROM coordinates WHERE id= '" . $locationid . "'";

        $result = mysqli_query($this->connect, $this->sql);
        $row = mysqli_fetch_assoc($result);

        $return_arr = array();

        if (mysqli_num_rows($result) != 0) {
            $dbcoordinateid = $row['id'];
            if($dbcoordinateid == $locationid)
                {
                array_push($return_arr, array(
                            'Id'=>$row['id'],
                            'Location'=>$row['Location'],
                            'Latitude'=>$row['Latitude'],
                            'Longitude'=>$row['Longitude']
                        ));
                }  
                else return false;
                } 
            else return false;

        return json_encode($return_arr);
    }

    function getData($table, $json, $locationid){

        $jsonString = $this->prepareData($json);
        //$jsonString = '{"type": "iBeacon","uuid":"10000000-0000-0000-0000-000000000000","major":2,"minor":0,"rssi":-91,"macAddress": "0c:f3:ee:16:91:8f"}';
        $locationid = $this->prepareData($locationid);
        $id;
        $location;
        $latitude;
        $longitude;

        /*GET LOCATION DATA*/
        $getlocation = $this->getLocation($locationid);

        /*IF LOCATION EXISTS ASSIGN DATA VALUES TO VARIABLES*/
        if($getlocation != 0){
            $result = json_decode($getlocation, true);
            foreach($result as $data){
                $id = $data['Id'];
                $location = $data['Location'];
                $latitude = $data['Latitude'];
                $longitude = $data['Longitude'];
            }
        }   else echo 'Location does not exist';

        /*GET MACADDRESS FROM JSON STRING*/
        $data = json_decode($jsonString);
        $macAddress = $data->{'macAddress'};


        if($this->ifExists($macAddress) == TRUE){
            echo 'Mac Already exists';
        }
        else
        $this->sql = "INSERT INTO " . $table . " (Location, TagID, Latitude, Longitude) VALUES ('" . $location . "','" . $macAddress . "','" . $latitude . "','" . $longitude . "')";

        if (mysqli_query($this->connect, $this->sql) === true) { 
            echo 'Registration Sucessful';
            return true;
        } else echo 'An error ocurr while registering the location';   
        
    }
                
}

?>