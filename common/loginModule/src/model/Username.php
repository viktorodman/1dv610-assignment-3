<?php

namespace Model;

require_once('RegistrationException.php');

class Username {
    private $username;

    public function __construct(string $username) {
        if (!preg_match('/^[a-zA-Z0-9]+$/', $username)) {
            throw new \Model\RegistrationException("Username contains invalid characters.");
        }
        $this->username = $username;
    }

    public function getUsername() : string {
        return $this->username;
    }


}