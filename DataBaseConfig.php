<?php

class DataBaseConfig
{
    public $servername;
    public $username;
    public $password;
    public $databasename;

    public function __construct()
    {
       

        $this->servername = 'proyecto-2-0.cb1dqjxth2qo.us-east-1.rds.amazonaws.com';
        $this->username = 'admin';
        $this->password = 'grodb1992';
        $this->databasename = 'Proyecto-2_0';

    }
}

?>
