<?php

require_once('model/DAL/UserSession.php');
require_once('model/DAL/UserCookieDAL.php');
require_once('model/DAL/UsersDAL.php');
require_once('model/UserCookie.php');
require_once('model/Credentials.php');
require_once('model/RegisterCredentials.php');
require_once('model/User.php');

class Authenticator {
    private $dbConnection;
    private $userSession;
    private $cookieDAL;
    private $usersDAL;

    public function __construct(\mysqli $dbConnection) {
        // TODO: Add something
        $this->dbConnection = $dbConnection;
        $this->userSession = new \Model\DAL\UserSession();
        $this->cookieDAL = new \Model\DAL\UserCookieDAL($dbConnection);
        $this->usersDAL = new \Model\DAL\UsersDAL($dbConnection);
    }

    public function attemptLogin(string $username, string $password) {
        // TODO: Try to login a user
        $userCredentials = new \Model\Credentials($username, $password);

        $this->usersDAL->loginUser(new \Model\User($userCredentials));
    }

    public function attemptLoginAndRememberUser(string $username, string $password) : \Model\UserCookie{
        // TODO: Try to login a user
        $this->attemptLogin($username, $password);
        
        $usercookie = new \Model\UserCookie($username);
        $this->cookieDAL->saveCookieInformation($usercookie);

        return $usercookie;
    }

    public function attemptLoginWithCookies(string $cookieUsername, string $cookiePassword) : \Model\UserCookie {
        // TODO: Try to login a user with cookies

       $updatedCookie = $this->cookieDAL->validateAndUpdateCookie($cookieUsername, $cookiePassword);

       return $updatedCookie;
    }

    public function attemptLogout(string $logoutMessage="") {
        // TODO: Try to logout a user
        $this->userSession->setSessionMessage($logoutMessage);
        $this->userSession->removeUserSession();
        $this->userSession->setMessageToBeViewed();
    }

    public function attemptRegisterUser(string $username, string $password, string $passwordRepeat) {
        // TODO: Try to register a new user
        $registerCredentials = new \Model\RegisterCredentials($username, $password, $passwordRepeat);
        $user = new \Model\User($registerCredentials->getUserCredentials());

        $this->usersDAL->registerUser($user);
    }

    public function remeberSuccessfullRegistration(string $username, string $successMessage="") {
        $this->setRemeberedUsername($username);
        $this->setRemeberedMessage($successMessage);
    }

    public function remeberFailedRegistration(string $remeberedUsername, string $errorMessage="") {
        $this->setRemeberedUsername($remeberedUsername);
        $this->setRemeberedMessage($errorMessage);
    }

    public function isLoggedIn()  : bool {
        // TODO: Check if a user is logged in
        return $this->userSession->userSessionIsActive();
    }

    public function remeberSuccessfulLogin(string $username, string $successMessage="") {
        $this->userSession->setSessionUser($username);
        $this->setRemeberedMessage($successMessage);
    }

    public function remeberFailedLogin(string $remeberedUsername, string $errorMessage="") {
        $this->setRemeberedUsername($remeberedUsername);
        $this->setRemeberedMessage($errorMessage);
    }

    public function getRemeberedUsername() : string {
        return $this->userSession->getRememberedUsername();
    }

    public function getSessionMessage () : string {
        // Get a session message
        return $this->userSession->getSessionMessage();
    }

    public function getSessionUser () : string {
        // Get a session user
        return $this->userSession->getRememberedUsername();;
    }

    public function getUser() : string {
        return $this->userSession->getSessionUser();
    }

    private function setRemeberedUsername($username) {
        $this->userSession->setRemeberedUsername($username);
        $this->userSession->setUsernameToBeRemembered();
    }

    private function setRemeberedMessage($message) {
        $this->userSession->setSessionMessage($message);
		$this->userSession->setMessageToBeViewed();
    }
}