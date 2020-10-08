<?php

namespace Controller;

require_once('model/User.php');

class Register {
    
    private $registerView;
    private $usersDAL;

    public function __construct(\View\Register $registerView, \Model\DAL\UsersDAL $usersDAL) {
        $this->registerView = $registerView;
        $this->usersDAL= $usersDAL;
    }

    public function doRegister() {
        if ($this->registerView->userWantsToRegister()) {
            try {
                $userCredentials = $this->registerView->getRegisterCredentials();
                $user = new \Model\User($userCredentials);
                $this->usersDAL->registerUser($user);
                $this->registerView->reloadPageAndNotifyRegisteredAccount();
                
                //  Try to register user on database
            } catch (\Throwable $error) {
                $this->registerView->reloadPageAndShowErrorMessage($error->getMessage());
            }
        }
        
    }
}