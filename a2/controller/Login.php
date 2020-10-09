<?php

namespace Controller;

require_once('model/User.php');
require_once('model/UserCookie.php');

class Login {

    private $loginView;
    private $authenticator;

    public function __construct(\View\Login $loginView, \Authenticator $authenticator) {
                                    
        $this->loginView = $loginView;
        $this->authenticator = $authenticator;
    }

    public function doLogout() {
        if ($this->authenticator->isLoggedIn()) {
            if ($this->loginView->userWantsToLogout()) {
                $this->loginView->reloadPageAndLogout();
            }
        }
    }

    public function doLogin() {
        try {
            if ($this->authenticator->isLoggedIn() === false) {
                if ($this->loginView->userWantsToLoginWithCookies()) {

                    $updatedCookieInformation = $this->authenticator->attemptLoginWithCookies(
                        $this->loginView->getCookieUsername(),
                        $this->loginView->getCookiePassword()
                    );
            
                    $this->loginView->setUserCookies($updatedCookieInformation);
                    $this->loginView->reloadPageAndLogin();

                } elseif($this->loginView->userWantsToLogin()){

                    
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
            $userCookie = $this->authenticator->attemptLoginAndRememberUser(
                $this->loginView->getRequestUsername(),
                $this->loginView->getRequestPassword()
            );
            $this->loginView->setUserCookies($userCookie);
        }
    }

    private function attemptLoginWithCookies() {
        
    }
}