<?php

namespace Model\DAL;

require_once(__DIR__ .  '/../LoginException.php');


class UserCookieDAL {
    private static $tableName = "cookies";
    private static $fieldCookiePassword = "cookiepassword";
    private static $fieldCookieUser = "cookieuser";
    private static $fieldExpireDate = "expiredate";
    private static $wrongInfoInCookiesMessage = "Wrong information in cookies";
    private $dbConnection;

    public function __construct(\mysqli $dbConnection) {
        $this->dbConnection = $dbConnection;
        $this->createCookieTableIfNeeded();
    }

    public function saveCookieInformation(\Model\UserCookie $userCookie) {
        if ($this->userCookieExists($userCookie->getCookieUsername())) {
            $this->updateAndSaveCookieInfo($userCookie);
        } else {
            $this->saveCookie($userCookie);
        }
    }

    public function validateAndUpdateCookie($cookieUsername, $cookiePassword) : \Model\UserCookie {
        if ($this->cookiesAreValid($cookieUsername, $cookiePassword)) {
            $updatedCookie = new \Model\UserCookie($cookieUsername);
            $this->updateAndSaveCookieInfo($updatedCookie);

            return $updatedCookie;
        } else {
            throw new \Model\LoginException(self::$wrongInfoInCookiesMessage);
        }
    }

    private function cookiesAreValid($cookieUsername, $cookiePassword) : bool {
        return $this->passwordIsValid($cookieUsername, $cookiePassword) &&
               $this->isCookieExpired($cookieUsername) === false;
    }

    public function updateAndSaveCookieInfo(\Model\UserCookie $userCookie) {
        $query = "UPDATE " . self::$tableName . 
                 " SET ". self::$fieldCookiePassword ."=?, ". self::$fieldExpireDate ."=?" . 
                 " WHERE ". self::$fieldCookieUser ."=?";

        if ($stmt = $this->dbConnection->prepare($query)) {
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

    public function getCookiePassword(string $cookieUsername) : string {
        $query = "SELECT ". self::$fieldCookiePassword .
                 " FROM " . self::$tableName . 
                 " WHERE ". self::$fieldCookieUser ."=?";

        if($stmt = $this->dbConnection->prepare($query)) {
            $stmt->bind_param("s", $cookieUsername);

            $stmt->execute();

            $stmt->bind_result($savedPassword);

            $stmt->fetch();

            $stmt->close();

            return $savedPassword;
        }
    }

    public function getCookieDuration(string $cookieUsername) : int {
        $query = "SELECT ". self::$fieldExpireDate .
                 " FROM " . self::$tableName . 
                 " WHERE ". self::$fieldCookieUser ."=?";

        if($stmt = $this->dbConnection->prepare($query)) {
            $stmt->bind_param("s", $cookieUsername);

            $stmt->execute();

            $stmt->bind_result($cookieDuration);

            $stmt->fetch();

            $stmt->close();

            return $cookieDuration;
        }
    }

    private function saveCookie(\Model\UserCookie $userCookie) {

        $query = "INSERT INTO " . self::$tableName . 
                 " (". self::$fieldCookieUser .
                 ", ". self::$fieldCookiePassword .
                 ", ". self::$fieldExpireDate .
                 ") VALUES (?,?,?)";

        if ($stmt = $this->dbConnection->prepare($query)) {
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

        $query = "SELECT * FROM " . self::$tableName . 
                 " WHERE ". self::$fieldCookieUser ."=?";
        $userExists = 0;
        
        if($stmt = $this->dbConnection->prepare($query)) {
            $stmt->bind_param("s", $cookieUsername);
            $stmt->execute();
            $stmt->store_result();
    
            $userExists = $stmt->num_rows;
            $stmt->close();
        }
       
        return $userExists == 1;
    }

    private function passwordIsValid(string $cookieUsername, string $cookiePassword) : bool {
        $query = "SELECT ". self::$fieldCookiePassword .
                 " FROM " . self::$tableName . 
                 " WHERE ". self::$fieldCookieUser ."=?";
        
        if($stmt = $this->dbConnection->prepare($query)) {
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


        $query = "SELECT ". self::$fieldExpireDate .
                 " FROM " . self::$tableName . 
                 " WHERE ". self::$fieldCookieUser ."=?";

        if ($stmt = $this->dbConnection->prepare($query)) {
            $stmt->bind_param("s", $cookieUsername);

            $stmt->execute();

            $stmt->bind_result($cookieExpiredate);

            $stmt->fetch();


            if ($cookieExpiredate > time()) {
                return false;
            }
        }

        return true;

    }


    private function createCookieTableIfNeeded() {
        $createTable = "CREATE TABLE IF NOT EXISTS " . self::$tableName . " (
            ". self::$fieldCookieUser ." VARCHAR(30) NOT NULL UNIQUE,
            ". self::$fieldCookiePassword ." VARCHAR(250) NOT NULL,
            ". self::$fieldExpireDate ." int(250)
            )";

            if($this->dbConnection->query($createTable)) {
                // Add message
             } else {
                 // Add error message
             }
    }
}