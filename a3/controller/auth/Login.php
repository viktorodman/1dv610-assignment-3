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
                    $this->loginWithCookies();

                    $this->loginView->reloadPageAndLogin();
                } else if($this->loginView->userWantsToLogin()){

                    if ($this->loginView->userWantsToBeRemembered()) {
                        $this->loginAndBeRemebered();
                    } else {
                        $this->authenticator->attemptLogin(
                            $this->loginView->getRequestUsername(),
                            $this->loginView->getRequestPassword(),
                            false
                        );
                    }

                    $this->loginView->reloadPageAndLogin();
                }
            }
        } catch(\Model\LoginException $credentialsException) {
            $this->loginView->reloadPageAndShowErrorMessage($credentialsException->getMessage());
        } catch (\Throwable $error) {
           throw $error;
        }
    }

    private function loginAndBeRemebered() {
        $username = $this->loginView->getRequestUsername();

        $this->authenticator->attemptLogin(
            $username,
            $this->loginView->getRequestPassword(),
            true
        );

        $this->loginView->setUserCookies(
            $username,
            $this->authenticator->getCookiePassword($username),
            $this->authenticator->getCookieDuration($username)
        );
    }

    private function loginWithCookies() {
        $cookieUser = $this->loginView->getCookieUsername();

        $this->authenticator->attemptLoginWithCookies(
            $cookieUser,
            $this->loginView->getCookiePassword()
        );
        
        $this->loginView->setUserCookies(
            $cookieUser,
            $this->authenticator->getCookiePassword($cookieUser),
            $this->authenticator->getCookieDuration($cookieUser)
        );
    }
}