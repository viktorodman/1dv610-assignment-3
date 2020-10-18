<?php

namespace Model;

require_once('RegistrationException.php');

class Password {
    private static $passwordToShortMessage = 'Password has too few characters, at least 6 characters.';
    private $password;

    public function __construct(string $password) {
        if (strlen($password) < 6) {
            throw new \Model\RegistrationException(self::$passwordToShortMessage);
        }

        $this->password = $password;
    }

    public function getPassword() : string {
        return $this->password;
    }

    
}