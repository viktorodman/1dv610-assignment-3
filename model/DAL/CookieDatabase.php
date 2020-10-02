<?php

namespace Model\DAL;

class CookieDatabase {
    private static $tableName = "cookies";
    private static $rowCookiePassword = "cookiepassword";
    private static $rowExpireDate = "expiredate";
    private static $wrongInfoInCookiesMessage = "Wrong information in cookies";
    private static $tableFields = "(
        cookieuser VARCHAR(30) NOT NULL UNIQUE,
        cookiepassword VARCHAR(250) NOT NULL,
        expiredate int(250)
        )";
    private $database;

    public function __construct(\Model\DAL\Database $database) {
        $this->database = $database;
        $this->database->createTableIfNeeded(self::$tableName, self::$tableFields);
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
        $queryString = "SET cookiepassword='". $password ."', expiredate='". $duration ."' WHERE cookieuser='". $username ."'";
        
        $this->database->updateValueInTable(self::$tableName, $queryString);
    }

    private function saveCookie(string $cookieUsername, string $cookiePassword, int $cookieDuration) {
        $query = "(cookieuser, cookiepassword, expiredate) VALUES 
        ('". $cookieUsername ."', '". $cookiePassword ."', '". $cookieDuration ."')";

        $this->database->insertValueToTable(self::$tableName, $query);
    }

    private function userCookieExists(string $cookieUsername) : bool {
        $query = "WHERE cookieuser LIKE BINARY '". $cookieUsername ."'";

        return $this->database->isValueInTable(self::$tableName, $query);
    }

    private function passwordIsValid(string $cookieUsername, string $cookiePassword) : bool {
        $query = "WHERE cookieuser LIKE BINARY '". $cookieUsername ."'";
        $savedPassword = $this->database->getValueFromTable(self::$tableName, $query, self::$rowCookiePassword);

        return $savedPassword[0] === $cookiePassword; 
    }

    private function cookieIsNotExpired(string $cookieUsername) : bool {
        $query = "WHERE cookieuser LIKE BINARY '". $cookieUsername ."'";

        $cookieExpiredate = $this->database->getValueFromTable(self::$tableName, $query, self::$rowExpireDate);

        if ($cookieExpiredate[0] < time()) {
           
            throw new \Exception(self::$wrongInfoInCookiesMessage);
        }

        return true;
    }
}