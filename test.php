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


        function setTagId($tagid, $vehicleid){

        $tagid = $this->prepareData($tagid);
        $vehicleid = $this->prepareData($vehicleid);  

        $this->sql = "INSERT INTO tags (Tag, vehicle_id) VALUES ('SUVGCLASSTAG02', '5')";

        if (mysqli_query($this->connect, $this->sql) === true) { 
            echo 'Registration Sucessful';
            return true;
        } else echo 'An error ocurr while registering the tag';   
        
        }


        function getTagId($table, $vehicleid) 
        {
        $vehicleid = $this->prepareData($vehicleid);

        
        $this->sql = "SELECT * FROM " . $table . " WHERE vehicle_id ='" . $vehicleid . "'";

        $result = mysqli_query($this->connect, $this->sql);
        $row = mysqli_fetch_assoc($result);

        var_dump($result);
        if (mysqli_num_rows($result) != 0) {
                $dbvehicle_id = $row['vehicle_id'];    

            if ($dbvehicle_id == $vehicleid) {
                $return_arr['tags'] = array();
                     array_push($return_arr['tags'], array(
                            'Tag_Id'=>$row['id'],
                            'Vehicle_Id'=>$row['vehicle_id']
                        ));
                    while($row = mysqli_fetch_assoc($result)){
                        array_push($return_arr['tags'], array(
                            'Tag_Id'=>$row['id'],
                            'Vehicle_Id'=>$row['vehicle_id']
                        ));
                    }
                    echo json_encode($return_arr);
                    return true;
                }
                else echo 'Error1';
            }
             else echo 'Error2'; 
        }


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

        /*TODO*/
        //TABLE Vehicles no longer has a column "user_id", I have created a pivot table with this relationship called "app_user_vehicle"
        //where we have the app_user_id and the vehicle_id
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
        "INSERT INTO " . $table . " (VIN, Make, Model, Year, Color, Type) VALUES ('" . $vin . "','" . $make . "','" . $model . "','" . $year . "','" . $color . "','" . $type . "')";
        
        if (mysqli_query($this->connect, $this->sql)) { 
            return true;
        } else return false;       
        
        $vehicleid = $this->getVehicleId($vin);
        $this->userVehicle($userid, $vehicleid);
        
        }

        function tagRegistration($table, $vin, $make, $model, $year, $color, $type, $tagid, $email){

        $vin = $this->prepareData($vin);
        $make = $this->prepareData($make);
        $model = $this->prepareData($model);
        $year = $this->prepareData($year);
        $color = $this->prepareData($color);
        $type = $this->prepareData($type);
        $tagid = $this->prepareData($tagid);
        $email = $this->prepareData($email);


        $this->assetRegistration('vehicles', $vin, $make, $model, $year, $color, $type, $tagid, $email);

        $response = $this->getVehicleId($vin);
            $result = json_decode($response, true);
            foreach ($result['vehicles'] as $element) {
                    $vehicleid = $element['Vehicle_Id'];
                    echo $vehicleid;
            }

        $this->setTagId($tagid, $vehicleid);
           
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

        $encoded_data = str_replace("'", '"', $jsonString);

        /*GET MACADDRESS FROM JSON STRING*/        
        //$data = json_encode($encoded_data);
        //$macAddress = $data->{"macAddress"};
        $macAddress = substr($encoded_data, 126, -3);

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
        }   else echo '<br>Location does not exist';

        

        if($this->ifExists($macAddress) === true){
            echo '<br>Mac Already exists';
        }
        else
        $this->sql = "INSERT INTO " . $table . " (Location, TagID, Latitude, Longitude) VALUES ('" . $location . "','" . $macAddress . "','" . $latitude . "','" . $longitude . "')";

        if (mysqli_query($this->connect, $this->sql) === true) { 
            echo '<br>Registration Sucessful';
            return true;
        } else echo '<br>An error ocurr while registering the location';   
        
    }

/*    function test($data){
        $data = $this->prepareData($data);
        $locationid = '1';
        $table = 'test';

        $encoded_data = str_replace("'", '"', $data);

        $this->sql = "INSERT INTO " . $table . " (data, location_id) VALUES ('" . $encoded_data . "','" . $locationid . "')";

         if (mysqli_query($this->connect, $this->sql) === true) { 
            echo 'Registration Sucessful';
            return true;
        } else echo 'An error ocurr while registering the location';

        echo($data);
    }*/

    /*************** GET VEHICLE VIN BASED ON TAG ID ***************/
    function getVehicleInfo($tagid){

        $tagid = $this->prepareData($tagid);
        $table = 'tags';
        $vehicle_id;

        $this->sql = "select * from " . $table . " where Tag = '" . $tagid . "'";
        
        $result = mysqli_query($this->connect, $this->sql);
        $row = mysqli_fetch_assoc($result);

        if (mysqli_num_rows($result) != 0) {
            $dbtag = $row['Tag'];
            $dbvehicleid = $row['vehicle_id'];
            if ($dbtag == $tagid) {
               $vehicle_id = $dbvehicleid;
                return $vehicle_id; 
            }                                                      
                else return false;
        } 
        else return false;
    }


    /*************** GET VEHICLE VIN (CREATE REPORT ENTRY) ***************/
    function getVehicleVIN($vehicleid){

        $vehicleid = $this->prepareData($vehicleid);
        $table = 'vehicles';
        $vin;

        $this->sql = "select * from " . $table . " where id = '" . $vehicleid . "'";
        
        $result = mysqli_query($this->connect, $this->sql);
        $row = mysqli_fetch_assoc($result);

        if (mysqli_num_rows($result) != 0) {
            $dbid = $row['id'];
            $dbvin = $row['VIN'];
            if ($dbid == $vehicleid) {
               $vin = $dbvin;
                return $vin; 
            }                                                      
                else return false;
        } 
        else return false;
    }

     /*************** LOOK FOR VIN IN REPORTS TABLE ***************/
    function VINExists($vin){

        $vin = $this->prepareData($vin);
        $table = 'reports';

        $this->sql = "select * from " . $table . " where VIN = '" . $vin . "'";
        
        $result = mysqli_query($this->connect, $this->sql);
        $row = mysqli_fetch_assoc($result);

        if (mysqli_num_rows($result) != 0) {
            $dbvin = $row['VIN'];

            if ($dbvin == $vin) {
                return true;    
            }                         
                else return false;
        } 
        else return false;
    }


    /*************** VALIDATE ENTRY TO CHECK IF TAG ID HAS BEEN REPORTED ***************/
    function validations($macAddress, $data, $locationid){

        $macAddress = $this->prepareData($macAddress);

        $vehicleid = $this->getVehicleInfo($macAddress);
        $vin = $this->getVehicleVIN($vehicleid);

        if($this->VINExists($vin) === true){
            $this->getData('locations', $data, $locationid);
        }
        else echo 'An error ocurr while registering the location';
    }

    function livefeed($data, $locationid){
        $data = $this->prepareData($data);
        $locationid = $this->prepareData($locationid);
        $table = 'livefeed';

        $encoded_data = str_replace("'", '"', $data);

        $macAddress = substr($encoded_data, 126, -3);        

        $this->sql = "INSERT INTO " . $table . " (Data, location_id) VALUES ('" . $encoded_data . "','" . $locationid . "')";

         if (mysqli_query($this->connect, $this->sql) === true) { 
            $this->validations($macAddress, $data, $locationid);   
            return true;
        } else echo 'An error ocurr while registering the data';



    }


}

?>