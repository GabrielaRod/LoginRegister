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

    /*************** GET VEHICLE ID ***************/
    function getVehicleId($table, $email){ //Retrieves all vehicle data related to the tags registered by the user

        $email = $this->prepareData($email);

        $userid = $this->getUserId('users', $email);


        /*To select vehicle id based on user_id*/
        $this->sql = "SELECT * FROM " . $table . " WHERE user_id ='" . $userid . "'";

        $result = mysqli_query($this->connect, $this->sql);
        $row = mysqli_fetch_assoc($result);

        if (mysqli_num_rows($result) != 0) {
                $dbvehicle_id = $row['id'];    
                $vehicleid = $dbvehicle_id;

            if ($dbvehicle_id == $vehicleid) {
                $return_arr['vehicles'] = array();
                     array_push($return_arr['vehicles'], array(
                            'Vehicle_Id'=>$row['id'],
                            'User_Id'=>$row['user_id']
                        ));
                    while($row = mysqli_fetch_assoc($result)){
                        array_push($return_arr['vehicles'], array(
                            'Vehicle_Id'=>$row['id'],
                            'User_Id'=>$row['user_id']
                        ));
                    }
                    return json_encode($return_arr);
                }
                else echo 'Error1';
            }
             else echo 'Error2'; 
    }

     /*************** GET TAG ID ***************/
    function getTagId($table, $email){
        $email = $this->prepareData($email);

        $response = $this->getVehicleId('vehicles', $email);
        $result = json_decode($response, true);

        $return_arr['tags'] = array();

            foreach ($result['vehicles'] as $element) {
                    $vehicleid = $element['Vehicle_Id']; 

                    /*To select vehicle id based on user_id*/
                    $this->sql = "SELECT * FROM " . $table . " WHERE vehicle_id ='" . $vehicleid . "'";

                    $result2 = mysqli_query($this->connect, $this->sql);
                    $row = mysqli_fetch_assoc($result2);

                    if (mysqli_num_rows($result2) != 0) {
                        $dbvehicle_id = $row['vehicle_id'];    

                    if ($dbvehicle_id == $vehicleid) {
                        array_push($return_arr['tags'], array(
                            'Tag'=>$row['Tag'],
                            'Vehicle_Id'=>$row['vehicle_id']
                            ));
                    while($row = mysqli_fetch_assoc($result2)){
                        array_push($return_arr['tags'], array(
                            'Tag'=>$row['Tag'],
                            'Vehicle_Id'=>$row['vehicle_id']
                            ));
                        }
                    
                    }
                    else echo 'Error1';      
                    }
                else echo '<br>';
                }
        echo json_encode($return_arr);
        
    }

}

?>