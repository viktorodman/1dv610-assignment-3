<?php

class Settings {
    private static $errorLogFileName = 'errorlog.txt';

    public function __construct() {
        $url = getenv('JAWSDB_URL2');
        $dbparts = parse_url($url);

        $hostname = $dbparts['host'];
        $username = $dbparts['user'];
        $password = $dbparts['pass'];
        $database = ltrim($dbparts['path'],'/');
        // Create connection
        $this->dbConnection = new \mysqli($hostname, $username, $password, $database);

        // Check connection
        if ($this->dbConnection->connect_error) {
            die("Connection failed: " . $this->dbConnection->connect_error);
        }
    }

    public function getDBConnection() : \mysqli {
        return $this->dbConnection;
    }

    public function getErrorLogFileName() : string {
		return self::$errorLogFileName;
	}

}