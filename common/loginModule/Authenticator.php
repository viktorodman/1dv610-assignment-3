<?php

class Authenticator {
    private $dbConnection;

    public function __construct(\mysqli $dbConnection) {
        // TODO: Add something
        $this->dbConnection = $dbConnection;
    }

    public function attemptLogin(string $username, string $password, bool $wantsToBeRemebered) {
        // TODO: Try to login a user
    }

    public function attemptLogout() {
        // TODO: Try to logout a user
    }

    public function isLoggedIn() {
        // TODO: Check if a user is logged in
    }

    public function attemptRegisterUser(string $username, string $password, string $passwordRepeat) {
        // TODO: Try to register a new user
    }
}