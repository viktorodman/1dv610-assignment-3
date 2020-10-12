<?php

namespace Controller\Auth;

class Login {

    private $loginView;
    private $authenticator;

    public function __construct(\View\Auth\Login $loginView, \Authenticator $authenticator) {
                                    
        $this->loginView = $loginView;
        $this->authenticator = $authenticator;
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
                    $this->attemptLogin();
                    $this->loginView->reloadPageAndLogin();
                }
            }
        } catch (\Throwable $error) {
            $this->loginView->reloadPageAndShowErrorMessage($error->getMessage());
        }
    }

    private function attemptLogin () {
        if ($this->loginView->userWantsToBeRemembered()) {
            $userCookie = $this->authenticator->attemptLoginAndRememberUser(
                $this->loginView->getRequestUsername(),
                $this->loginView->getRequestPassword()
            );
            $this->loginView->setUserCookies($userCookie);
        } else {
            $this->authenticator->attemptLogin(
                $this->loginView->getRequestUsername(),
                $this->loginView->getRequestPassword()
            );
        }
    }
}