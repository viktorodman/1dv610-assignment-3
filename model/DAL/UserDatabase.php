<?php

namespace Model\DAL;

class UserDatabase {
    private static $tableName = "Users";
    private static $rowUsername = "username";
    private static $rowPassword = "password";

    private static $tableFields = "(username VARCHAR(30) NOT NULL UNIQUE,password VARCHAR(60) NOT NULL)";
    
    private static $userAlreadyExistsMessage = "User exists, pick another username.";
    private static $wrongNameOrPasswordMessage = "Wrong name or password";
    private $database;

    public function __construct(\Model\DAL\Database $database) {
        $this->database = $database; 

        $this->database->createTableIfNeeded(self::$tableName, self::$tableFields);
    }

    public function registerUser(\Model\User $user) { 
        $credentials = $user->getCredentials();

        $username = $credentials->getUsername();
        $password = $credentials->getPassword();

        if($this->userExists($username)) {
            throw new \Exception(self::$userAlreadyExistsMessage);
            
        } else {
            $this->addUser($username, $password);
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

    private function addUser($username, $password) {
        $hash = $this->hashPassword($password);
        
        $query = "(" . self::$rowUsername . "," . self::$rowPassword . ") VALUES (" . "'" . $username ."', '". $hash ."')";
        $this->database->insertValueToTable(self::$tableName, $query);
    }

    private function hashPassword(string $password) : string {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    private function passwordIsCorrect(string $username, string $password) : bool {
        $queryString = "WHERE username LIKE BINARY '". $username ."'";
        $dbPassword = $this->database->getValueFromTable(self::$tableName, $queryString, self::$rowPassword);

        return \password_verify($password, $dbPassword[0]);
    }

    private function userExists(string $username) : bool {
        $query = "WHERE username LIKE BINARY '". $username ."'";

        return $this->database->isValueInTable(self::$tableName, $query);
    }
}