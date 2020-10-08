<?php

namespace Model;

class RegisterCredentials {
    private static $usernameToShortMessage = 'Username has too few characters, at least 3 characters.';

    private $username;
    private $password;
    private $repeatedPassword;


    public function __construct(string $username, string $password, string $repeatedPassword) {
        if (strlen($username) < 3) {
            throw new \Exception(self::$usernameToShortMessage);
        }


        $this->username = $username;
        $this->password = $password;
        $this->repeatedPassword = $repeatedPassword;
    }
}