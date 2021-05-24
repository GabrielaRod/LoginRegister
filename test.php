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

    function getVehicleId($table, $userid){

        $userid = $this->prepareData($userid);

        $this->sql = "select * from " . $table . " where user_id = '" . $userid . "'";
        $result = mysqli_query($this->connect, $this->sql);
        $row = mysqli_fetch_assoc($result);
        if (mysqli_num_rows($result) != 0) {
                $dbuser_id = $row['user_id'];    
                var_dump(mysqli_fetch_assoc($result));
            if ($dbuser_id == $userid) {
                $return_arr['tags'] = array();
                    while($row = mysqli_fetch_assoc($result)){
                        array_push($return_arr['tags'], array(
                            'Vehicle Id'=>$row['id'],
                            'User id'=>$row['user_id']
                        ));
                    }
                    echo json_encode($return_arr);
                }
                else echo 'Error1';
            }
             else echo 'Error2'; 
        }
        
    }

?>