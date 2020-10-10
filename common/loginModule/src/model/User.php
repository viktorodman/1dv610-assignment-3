<?php

namespace Model;

class User {
    private static $errorMessage = "Wrong name or password";

    private $userCredentials;

    public function __construct(\Model\Credentials $userCredentials) {
        $this->userCredentials = $userCredentials;
    }

    public function getCredentials() : \Model\Credentials {
        return $this->userCredentials;
    }
}