<?php

require_once('model/DAL/UserSession.php');
require_once('model/DAL/BrowserSessionStorage.php');
require_once('model/DAL/UserCookieDAL.php');
require_once('model/DAL/UsersDAL.php');
require_once('model/UserCookie.php');
require_once('model/Credentials.php');
require_once('model/RegisterCredentials.php');
require_once('model/User.php');

class Authenticator {
    private $dbConnection;
    private $userSession;
    private $browserSessionStorage;
    private $cookieDAL;
    private $usersDAL;

    public function __construct(\mysqli $dbConnection) {
        $this->dbConnection = $dbConnection;
        $this->browserSessionStorage = new \Model\DAL\BrowserSessionStorage();
        $this->userSession = new \Model\DAL\UserSession();
        $this->cookieDAL = new \Model\DAL\UserCookieDAL($dbConnection);
        $this->usersDAL = new \Model\DAL\UsersDAL($dbConnection);
    }

    
    public function isLoggedIn() : bool {
        return $this->userSession->userSessionIsActive() and $this->browserSessionStorage->sessionBrowserIsUpToDate();
    }

    /**
     * @throws LoginException
     */
    public function attemptLogin(string $username, string $password, bool $userWantsToBeRemembered) {
        $userCredentials = new \Model\Credentials($username, $password);
        $this->usersDAL->loginUser(new \Model\User($userCredentials));

        if ($userWantsToBeRemembered) {
            $usercookie = new \Model\UserCookie($username);
            $this->cookieDAL->saveCookieInformation($usercookie);
        }

        $this->loginUser($username);
    }

    /**
     * @throws LoginException
     */
    public function attemptLoginWithCookies($cookieUsername, $cookiePassword) {
        $this->cookieDAL->validateAndUpdateCookie($cookieUsername, $cookiePassword);
        
        $this->loginUser($cookieUsername);
    }

    public function attemptLogout() {
        $this->userSession->removeUserSession();
        $this->browserSessionStorage->unsetSessionBrowser();
    }

    public function getCookiePassword(string $cookieUsername) : string {
        return $this->cookieDAL->getCookiePassword($cookieUsername);
    }

    public function getCookieDuration(string $cookieUsername) : int {
        return $this->cookieDAL->getCookieDuration($cookieUsername);
    }

    /**
     * @throws RegisterException
     */
    public function attemptRegisterUser(string $username, string $password, string $passwordRepeat) {
        $registerCredentials = new \Model\RegisterCredentials($username, $password, $passwordRepeat);
        $user = new \Model\User($registerCredentials->getUserCredentials());

        $this->usersDAL->registerUser($user);
    }

    public function getUser() : string {
        return $this->userSession->getSessionUser();
    }

    private function loginUser(string $username) {
        $this->userSession->setSessionUser($username);
        $this->browserSessionStorage->setSessionBrowser();
    }
}