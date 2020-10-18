<?php

namespace Model\DAL;

class UserSession {
    
    private static $userSessionIndex = "userSessionIndex";

    public function getSessionUser() : string {
        return $_SESSION[self::$userSessionIndex];
    }


    public function userSessionIsActive() : bool {
        return isset($_SESSION[self::$userSessionIndex]);
    }

    public function setSessionUser(string $id) {
        $_SESSION[self::$userSessionIndex] = $id;
    }


    public function removeUserSession() {
        unset($_SESSION[self::$userSessionIndex]);
    }

}