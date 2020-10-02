<?php

namespace Model\DAL;

class CookieDatabase {
    private static $tableName = "cookies";
    private static $wrongInfoInCookiesMessage = "Wrong information in cookies";
    private $connection;

    public function __construct(\mysqli $dbConnection) {
        $this->connection = $dbConnection;
        $this->createCookieTableIfNeeded();
    }

    public function saveCookieInformation(string $cookieUsername, string $cookiePassword, int $cookieDuration) {
        if ($this->userCookieExists($cookieUsername)) {
            $this->updateAndSaveCookieInfo($cookieUsername, $cookiePassword, $cookieDuration);
        } else {
            $this->saveCookie($cookieUsername, $cookiePassword, $cookieDuration);
        }
    }

    public function cookiesAreValid($cookieUsername, $cookiePassword) : bool {
        if ($this->passwordIsValid($cookieUsername, $cookiePassword) && $this->cookieIsNotExpired($cookieUsername)) {
            
           return true;
        } else {
            throw new \Exception(self::$wrongInfoInCookiesMessage);
        }
    }

    public function updateAndSaveCookieInfo(string $username, string $password, int $duration) {
        $query = "UPDATE " . self::$tableName . " SET cookiepassword='". $password ."', expiredate='". $duration ."' WHERE cookieuser='". $username ."'";
        
        $this->connection->query($query);
    }

    private function saveCookie(string $cookieUsername, string $cookiePassword, int $cookieDuration) {
        $query = "INSERT INTO " . self::$tableName . " (cookieuser, cookiepassword, expiredate) VALUES
            ('". $cookieUsername ."', '". $cookiePassword ."', '". $cookieDuration ."')";

            $this->connection->query($query);
    }

    private function userCookieExists(string $cookieUsername) : bool {
        $query = "SELECT * FROM " . self::$tableName . " WHERE cookieuser LIKE BINARY '". $cookieUsername ."'";
        $userExists = 0;
        
        if($stmt = $this->connection->prepare($query)) {
            $stmt->execute();
            $stmt->store_result();
    
            $userExists = $stmt->num_rows;
            $stmt->close();
        }
       
        return $userExists == 1;
    }

    private function passwordIsValid(string $cookieUsername, string $cookiePassword) : bool {
        $query = "SELECT cookiepassword FROM " . self::$tableName . " WHERE cookieuser LIKE BINARY '". $cookieUsername ."'";
        $savedPassword = $this->connection->query($query);
        $savedPassword = \mysqli_fetch_row($savedPassword);

        return $savedPassword[0] === $cookiePassword; 
    }

    private function cookieIsNotExpired(string $cookieUsername) : bool {
        $query = "SELECT expiredate FROM " . self::$tableName . " WHERE cookieuser LIKE BINARY '". $cookieUsername ."'";
        $cookieExpiredate = $this->connection->query($query);

        $cookieExpiredate = \mysqli_fetch_row($cookieExpiredate);

        if ($cookieExpiredate[0] < time()) {
           
            throw new \Exception(self::$wrongInfoInCookiesMessage);
        }

        return true;
    }


    private function createCookieTableIfNeeded() {
        $createTable = "CREATE TABLE IF NOT EXISTS " . self::$tableName . " (
            cookieuser VARCHAR(30) NOT NULL UNIQUE,
            cookiepassword VARCHAR(250) NOT NULL,
            expiredate int(250)
            )";

            $this->connection->query($createTable);
    }
}