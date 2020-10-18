<?php

namespace Controller\Auth;


class Register {
    private $registerView;
    private $authenticator;

    public function __construct(\View\Auth\Register $registerView, \Authenticator $authenticator) {
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
            } catch (\Model\RegistrationException $regError) {
                $this->registerView->reloadPageAndShowErrorMessage($regError->getMessage());
            } catch (\Throwable $error) {
                // Write to errorLog
            }
        }
    }
}