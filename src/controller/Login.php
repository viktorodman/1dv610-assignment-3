<?php

namespace Controller;

require_once('model/User.php');
require_once('model/UserCookie.php');

class Login {

    private $loginView;
    private $usersDAL;
    private $cookieDAL;

    public function __construct(\View\Login $loginView,
                                \Model\DAL\UsersDAL $usersDAL,
                                \Model\DAL\UserCookieDAL $cookieDAL) {
                                    
        $this->loginView = $loginView;
        $this->usersDAL = $usersDAL;
        $this->cookieDAL = $cookieDAL;
    }

    public function doLogout() {
        if ($this->loginView->userHasActiveSession()) {
            if ($this->loginView->userWantsToLogout()) {
                $this->loginView->reloadPageAndLogout();
            }
        } 
    }

    public function doLogin() {
        try {
            if (!$this->loginView->userHasActiveSession()) {
                if ($this->loginView->userWantsToLogin()) {

                    if ($this->loginView->userWantsToLoginWithCookies()) {
                        $this->attemptLoginWithCookies();
                    } else {
                        $this->attemptLogin();
                    }
                    $this->loginView->reloadPageAndLogin();
                }
            }
        } catch (\Throwable $error) {
            $this->loginView->reloadPageAndShowErrorMessage($error->getMessage());
        }
    }

    private function attemptLogin () {
        $userCredentials = $this->loginView->getRequestUserCredentials();

        $this->usersDAL->loginUser(new \Model\User($userCredentials));
        
        if ($this->loginView->userWantsToBeRemembered()) {
            $userCookie = new \Model\UserCookie($userCredentials->getUsername());

            $this->cookieDAL->saveCookieInformation($userCookie);
            $this->loginView->setUserCookies($userCookie);
        }   
    }

    private function attemptLoginWithCookies() {
        $cookieUsername = $this->loginView->getCookieUsername();
        $cookiePassword = $this->loginView->getCookiePassword();

        if ($this->cookieDAL->cookiesAreValid($cookieUsername, $cookiePassword)) {
            $userCookie = new \Model\UserCookie($cookieUsername);

            $this->cookieDAL->updateAndSaveCookieInfo($userCookie);
            $this->loginView->setUserCookies($userCookie);
        } else {
            throw new \Exception("Wrong information in cookies");
        }
        
    }
}