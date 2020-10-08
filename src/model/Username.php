<?php

namespace Model;

class Username {
    private $username;

    public function __construct(string $username) {
        if (!preg_match('/^[a-zA-Z0-9]+$/', $username)) {
            throw new \Exception("Username contains invalid characters.");
        }
        $this->username = $username;
    }

    public function getUsername() : string {
        return $this->username;
    }


}