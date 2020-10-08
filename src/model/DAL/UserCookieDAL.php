<?php

namespace Model\DAL;

class UserCookieDAL {
    private static $tableName = "cookies";
    private static $rowCookiePassword = "cookiepassword";
    private static $wrongInfoInCookiesMessage = "Wrong information in cookies";
    private $settings;

    public function __construct(\Settings $settings) {
        $this->settings = $settings;
        $this->createCookieTableIfNeeded();
    }

    public function saveCookieInformation(\Model\UserCookie $userCookie) {


        if ($this->userCookieExists($userCookie->getCookieUsername())) {
            $this->updateAndSaveCookieInfo($userCookie);
        } else {
            $this->saveCookie($userCookie);
        }
    }

    public function cookiesAreValid($cookieUsername, $cookiePassword) : bool {
        return $this->passwordIsValid($cookieUsername, $cookiePassword) &&
               $this->isCookieExpired($cookieUsername) === false;
    }

    public function updateAndSaveCookieInfo(\Model\UserCookie $userCookie) {
        $dbConnection = $this->settings->getDBConnection();

        $query = "UPDATE " . self::$tableName . " SET cookiepassword=?, expiredate=? WHERE cookieuser=?";

        if ($stmt = $dbConnection->prepare($query)) {
            $stmt->bind_param(
                "sis",
                $userCookie->getCookiePassword(),
                $userCookie->getCookieDuration(),
                $userCookie->getCookieUsername()
            );
            
            $stmt->execute();
            $stmt->close();
        } else {
            // Todo Add error message
        } 
    }

    private function saveCookie(\Model\UserCookie $userCookie) {
        $dbConnection = $this->settings->getDBConnection();

        $query = "INSERT INTO " . self::$tableName . " (cookieuser, cookiepassword, expiredate) VALUES (?,?,?)";

        if ($stmt = $dbConnection->prepare($query)) {
            $stmt->bind_param(
                "ssi",
                $userCookie->getCookieUsername(),
                $userCookie->getCookiePassword(),
                $userCookie->getCookieDuration()
            );
            $stmt->execute();
            $stmt->close();
        } else {
            // Todo Add error message
        }
    }

    private function userCookieExists(string $cookieUsername) : bool {
        $dbConnection = $this->settings->getDBConnection();

        $query = "SELECT * FROM " . self::$tableName . " WHERE cookieuser=?";
        $userExists = 0;
        
        if($stmt = $dbConnection->prepare($query)) {
            $stmt->bind_param("s", $cookieUsername);
            $stmt->execute();
            $stmt->store_result();
    
            $userExists = $stmt->num_rows;
            $stmt->close();
        }
       
        return $userExists == 1;
    }

    private function passwordIsValid(string $cookieUsername, string $cookiePassword) : bool {
        $dbConnection = $this->settings->getDBConnection();
        $query = "SELECT ". self::$rowCookiePassword ." FROM " . self::$tableName . " WHERE cookieuser=?";
        
        if($stmt = $dbConnection->prepare($query)) {
            $stmt->bind_param("s", $cookieUsername);

            $stmt->execute();

            $stmt->bind_result($savedPassword);

            $stmt->fetch();

            if ($savedPassword === $cookiePassword) {
                $stmt->close();
                return true;
            }
        }
        $stmt->close();
        return false;
    }

    private function isCookieExpired(string $cookieUsername) : bool {

        $dbConnection = $this->settings->getDBConnection();

        $query = "SELECT expiredate FROM " . self::$tableName . " WHERE cookieuser=?";

        if ($stmt = $dbConnection->prepare($query)) {
            $stmt->bind_param("s", $cookieUsername);

            $stmt->execute();

            $stmt->bind_result($cookieExpiredate);

            if ($cookieExpiredate > time()) {
                return false;
            }
        }

        return true;

    }


    private function createCookieTableIfNeeded() {
        $dbConnection = $this->settings->getDBConnection();

        $createTable = "CREATE TABLE IF NOT EXISTS " . self::$tableName . " (
            cookieuser VARCHAR(30) NOT NULL UNIQUE,
            cookiepassword VARCHAR(250) NOT NULL,
            expiredate int(250)
            )";

            if($dbConnection->query($createTable)) {
                // Add message
             } else {
                 // Add error message
             }
    }
}