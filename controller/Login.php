<?php

namespace Controller;

require_once('model/User.php');


class Login {

    private $loginView;
    private $userDatabase;

    public function __construct(\View\Login $loginView, \Model\DAL\UserDatabase $userDB) {
        $this->loginView = $loginView;
        $this->userDatabase = $userDB;
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

                if ($this->loginView->userWantsToLoginWithCookies()) {
                    $this->loginView->validateCookies();
                    $this->loginView->reloadPageAndLoginWithCookie();

                } else if ($this->loginView->userWantsToLogin()) {
                    
                    $userCredentials = $this->loginView->getRequestUserCredentials();
                    $user = new \Model\User($userCredentials);
                    $this->userDatabase->loginUser($user);


                    if ($this->loginView->userWantsToBeRemembered()) {
                        $this->loginView->createUserCookie();
                        $this->loginView->reloadPageAndLoginWithCookie();
                    } else {
                        $this->loginView->reloadPageAndLogin();
                    }
                }
            }
        } catch (\Throwable $error) {
            $this->loginView->reloadPageAndShowErrorMessage($error->getMessage());
        }
    }
}