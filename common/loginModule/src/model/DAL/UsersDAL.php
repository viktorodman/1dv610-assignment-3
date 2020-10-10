<?php

namespace Model\DAL;

class UsersDAL {
    private static $tableName = "Users";
    private static $rowUsername = "username";
    private static $rowPassword = "password";
    private static $userAlreadyExistsMessage = "User exists, pick another username.";
    private static $wrongNameOrPasswordMessage = "Wrong name or password";
    private $dbConnection;

    public function __construct(\mysqli $dbConnection) {
        $this->dbConnection = $dbConnection;
        $this->createUserTableIfNeeded();
    }

    public function registerUser(\Model\User $user) { 
        $username = $user->getCredentials()->getUsername();
        $password = $user->getCredentials()->getPassword();

        if($this->isUserInDB($username) === false) {
            $this->addUserToDatabase($username, $password);
        } else {
            throw new \Exception(self::$userAlreadyExistsMessage);
        }
    }

    public function loginUser(\Model\User $user) {
        $username = $user->getCredentials()->getUsername();
        $password = $user->getCredentials()->getPassword();

        if ($this->isUserInDB($username)) {
            if (!$this->passwordIsCorrect($username, $password)) {
                throw new \Exception(self::$wrongNameOrPasswordMessage);
            } 
        } else {
            throw new \Exception(self::$wrongNameOrPasswordMessage);
        }
    }

    private function addUserToDatabase(string $username, string $password) {
        
        $hashedPassword = $this->hashPassword($password);

        $query = "INSERT INTO " . self::$tableName . " (username, password) VALUES (?, ?)";

        if ($stmt = $this->dbConnection->prepare($query)) {
            $stmt->bind_param("ss", $username, $hashedPassword);
            $stmt->execute();
            $stmt->close();
        } else {
            // Todo Add error message
        }
    }

    private function hashPassword(string $password) : string {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    private function passwordIsCorrect(string $username, string $password) : bool {
        
        $query = "SELECT ". self::$rowPassword ." FROM " . self::$tableName . " WHERE username=?";
        
        if($stmt = $this->dbConnection->prepare($query)) {
            $stmt->bind_param("s", $username);

            $stmt->execute();

            $stmt->bind_result($savedPassword);

            $stmt->fetch();

            if (\password_verify($password, $savedPassword)) {
                $stmt->close();
                return true;
            }
        }
        $stmt->close();
        return false;
    }

    private function isUserInDB(string $username) : bool {
        
        $query = "SELECT * FROM " . self::$tableName . " WHERE username LIKE BINARY '". $username ."'";
        $userExists = 0;
        
        if($stmt = $this->dbConnection->prepare($query)) {
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

        if($this->dbConnection->query($createTable)) {
           // Add message
        } else {
            // Add error message
        }
    }
}