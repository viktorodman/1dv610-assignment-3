<?php

namespace Controller;

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
                $this->authenticator->attemptRegisterUser(
                    $this->registerView->getRequestUsername(),
                    $this->registerView->getRequestPassword(),
                    $this->registerView->getRequestRepeatedPassword()
                );

                $this->registerView->reloadPageAndRemeberRegisteredAccount();
            } catch (\Model\RegistrationException $regException) {
                $this->registerView->reloadPageAndShowErrorMessage($regException->getMessage());
            } catch (\Throwable $error) {
                throw $error;
            }
        }
    }
}