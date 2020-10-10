<?php

namespace Model;

require_once('Username.php');
require_once('Password.php');

class Credentials {
    private static $errorMessageNoUsername = 'Username is missing';
    private static $errorMessageNoPassword = 'Password is missing';
    private $username;
    private $password;

    public function __construct(string $username, string $password) {
        if (empty($username)) {
            throw new \Exception(self::$errorMessageNoUsername);
        }
        
        if (empty($password)) {
            throw new \Exception(self::$errorMessageNoPassword);
		}

        $this->username = new \Model\Username($username);
        $this->password = new \Model\Password($password);
    }

    public function getUsername() : string {
        return $this->username->getUsername();
    }

    public function getPassword() : string {
        return $this->password->getPassword();
    }
}