<?php

namespace Model;

require_once('Credentials.php');
require_once('RegistrationException.php');

class RegisterCredentials {
    private static $passwordToShortMessage = 'Password has too few characters, at least 6 characters.';
    private static $usernameToShortMessage = 'Username has too few characters, at least 3 characters.';
    private static $passwordDoesNotMatchMessage = 'Passwords do not match.';
    
    private $credentials;

    public function __construct(string $username, string $password, string $repeatedPassword) {

        if(strlen($username) < 3 and strlen($password) < 6) {
            throw new \Model\RegistrationException(self::$usernameToShortMessage . '<br>' . self::$passwordToShortMessage);
        }
        if (strlen($username) < 3) {
            throw new \Model\RegistrationException(self::$usernameToShortMessage);
        }

        if (strlen($password) < 6) {
            throw new \Model\RegistrationException(self::$passwordToShortMessage);
        }

        if ($password !== $repeatedPassword) {
            throw new \Model\RegistrationException(self::$passwordDoesNotMatchMessage);
        }

        $this->credentials = new \Model\Credentials($username, $password);
    }

    public function getUserCredentials() : \Model\Credentials {
        return $this->credentials;
    }
}