<?php

namespace Model\DAL;

class UserDatabase {
    private static $tableName = "Users";
    private static $rowUsername = "username";
    private static $rowPassword = "password";
    private static $userAlreadyExistsMessage = "User exists, pick another username.";
    private static $wrongNameOrPasswordMessage = "Wrong name or password";
    private $connection;

    public function __construct(\mysqli $dbconnection) {
        $this->connection = $dbconnection; 

        $this->createUserTableIfNeeded();
    }


    public function registerUser(\Model\User $user) { 
        $credentials = $user->getCredentials();

        $username = $credentials->getUsername();
        $password = $credentials->getPassword();

        if($this->userExists($username)) {
            throw new \Exception(self::$userAlreadyExistsMessage);
            
        } else {
            $hash = $this->hashPassword($password);
            $query = "INSERT INTO " . self::$tableName . " (username, password) VALUES ('". $username ."', '". $hash ."')";
            $this->connection->query($query);
        }
    }

    public function loginUser(\Model\User $user) {
        $username = $user->getCredentials()->getUsername();
        $password = $user->getCredentials()->getPassword();

        if ($this->userExists($username)) {
            if (!$this->passwordIsCorrect($username, $password)) {
                throw new \Exception(self::$wrongNameOrPasswordMessage);
            } 
        } else {
            throw new \Exception(self::$wrongNameOrPasswordMessage);
        }
    }

    private function hashPassword(string $password) : string {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    private function passwordIsCorrect(string $username, string $password) : bool {
        $query = "SELECT ". self::$rowPassword ." FROM " . self::$tableName . " WHERE username LIKE BINARY '". $username ."'";
        
        $stmt = $this->connection->query($query);
        $stmt = \mysqli_fetch_row($stmt);

        return \password_verify($password, $stmt[0]);
    }

    private function userExists(string $username) : bool {
        $query = "SELECT * FROM " . self::$tableName . " WHERE username LIKE BINARY '". $username ."'";
        $userExists = 0;
        
        if($stmt = $this->connection->prepare($query)) {
            $stmt->execute();
            $stmt->store_result();
    
            $userExists = $stmt->num_rows;
            $stmt->close();
        }
       
        return $userExists == 1;
    }

    private function createUserTableIfNeeded() {
        $createTable = "CREATE TABLE IF NOT EXISTS " . self::$tableName . " (
            username VARCHAR(30) NOT NULL UNIQUE,
            password VARCHAR(60) NOT NULL
            )";

        if($this->connection->query($createTable)) {
           // Add message
        } else {
            // Add error message
        }
    }
}