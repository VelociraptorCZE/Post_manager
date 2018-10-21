<?php
/** Post manager
 *  Copyright (C) Simon Raichl 2018
 *  MIT License
 *  Use this as you want, share it as you want, do basically whatever you want with this :)
 */

class Vendor
{
    private $configPath = "_config/config";

    public function run()
    {
        mysqli_report(MYSQLI_REPORT_STRICT | MYSQLI_REPORT_ALL);
        try{
            $this->connect();
        }
        catch (mysqli_sql_exception $e){
            echo "Unable to connect to a database, <strong>please check your config file</strong>.<h3>DUMP</h3>" . $e;
        }
        mysqli_report(MYSQLI_REPORT_OFF);
    }

    public function escape($string){
        $patterns = array(array("/</", "&lt;"), array("/>/", "&gt;"), array('/"/', "&quot;"), array("/'/", "&apos;"));
        $finalString = $string;
        for ($i = 0; $i < count($patterns); $i++){
            $finalString = preg_replace($patterns[$i][0], $patterns[$i][1], $finalString);
        }
        return $finalString;
    }

    public function connect(){
        $db = new Db();
        return mysqli_connect(
            $db->get()->host,
            $db->get()->username,
            $db->get()->password,
            $db->get()->dbName
        );
    }

    public function fetchAll($query){
        $connection = $this->connect();
        $results = mysqli_fetch_all($connection->query($query));
        $connection->close();
        return $results;
    }

    public function returnConfig(){
        $config = fopen($this->configPath, "r");
        $configAsArray = explode("\n", fread($config, filesize($this->configPath)));
        fclose($config);
        return $configAsArray;
    }
}

final class Db
{
    public function get()
    {
        return new class() extends Vendor{
            public $host;
            public $dbName;
            public $username;
            public $password;

            public function __construct()
            {
                $this->host = $this->getDataFromConfig("host");
                $this->dbName = $this->getDataFromConfig("db");
                $this->username = $this->getDataFromConfig("username");
                $this->password = $this->getDataFromConfig("password");
            }

            private function getDataFromConfig($name)
            {
                $keys = ["host", "db", "username", "password"];
                $config = $this->returnConfig();
                foreach ($config as $prop)
                {
                    $param = explode(":", $prop);
                    foreach ($keys as $key){
                        if ($key == $param[0] && $name == $param[0])
                        {
                            return preg_replace("/\s/", "", $param[1]);
                        }
                    }
                }
                return null;
            }
        };
    }
}