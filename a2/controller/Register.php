<?php

namespace Controller;

require_once('model/User.php');

class Register {
    
    private $registerView;
    private $authenticator;

    public function __construct(\View\Register $registerView, \Authenticator $authenticator) {
        $this->registerView = $registerView;
        $this->authenticator= $authenticator;
    }

    public function doRegister() {
        if ($this->registerView->userWantsToRegister()) {
            try {
                $registerCredentials = $this->registerView->getRegisterCredentials();

                $user = new \Model\User($registerCredentials->getUserCredentials());

                $this->usersDAL->registerUser($user);
                $this->registerView->reloadPageAndNotifyRegisteredAccount();
                
                //  Try to register user on database
            } catch (\Throwable $error) {
                $this->registerView->reloadPageAndShowErrorMessage($error->getMessage());
            }
        }
    }
}