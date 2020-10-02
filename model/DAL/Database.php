<?php

namespace Model\DAL;

class Database {
    private $connection;


    public function __construct() {
        $url = getenv('JAWSDB_URL');
        $dbparts = parse_url($url);

        $hostname = $dbparts['host'];
        $username = $dbparts['user'];
        $password = $dbparts['pass'];
        $database = ltrim($dbparts['path'],'/');
        // Create connection
        $this->connection = new \mysqli($hostname, $username, $password, $database);

        // Check connection
        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        } 
    }

    public function getConnection() : \mysqli {
        return $this->connection;
    }

    public function insertValueToTable(string $table, string $queryString) {
        $query = "INSERT INTO " . $table . " " . $queryString;
        $this->connection->query($query);
    }

    public function updateValueInTable(string $table, string $queryString) {
        $query = "UPDATE " . self::$tableName . " " . $query;
        $this->connection->query($query);
    }

    public function getValueFromTable(string $table, string $queryString, string $field) {
        $query = "SELECT ". $field ." FROM " . $table . " " . $queryString;
        $stmt = $this->connection->query($query);
        return \mysqli_fetch_row($stmt);
    }

    public function isValueInTable(string $table, string $queryString) : bool {
        $query = "SELECT * FROM " . $table . " " . $queryString;
        $valueExists = 0;
        
        if($stmt = $this->connection->prepare($query)) {
            $stmt->execute();
            $stmt->store_result();
    
            $valueExists = $stmt->num_rows;
            $stmt->close();
        }

        return $valueExists == 1;
    }

    public function createTableIfNeeded(string $table, string $fields) {
        $createTable = "CREATE TABLE IF NOT EXISTS " . $table . " " . $fields;

        if($this->connection->query($createTable)) {
           // Add message
        } else {
            // Add error message
        }
    }
}