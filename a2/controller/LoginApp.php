<?php

namespace Controller;

require_once('controller/Login.php');
require_once('controller/Register.php');
require_once('View/Layout.php');
require_once('View/Login.php');
require_once('View/Register.php');
require_once('View/DateTime.php');

class LoginApp {
    private $authenticator;
    private $isLoggedIn;
    private $sessionHandler;
    private $loginView;
    private $registerView;

    public function __construct(\Authenticator $authenticator, \SessionStorageHandler $sessionHandler) {
        $this->authenticator = $authenticator;
        $this->sessionHandler = $sessionHandler;

        $this->loginView = new \View\Login($sessionHandler, $authenticator->isLoggedIn());
        $this->registerView = new \View\Register($sessionHandler);
    }

    public function run() {
        $this->loadState();

        $this->handleInput();

        $this->generateOutput();
    }

    private function loadState() {
       $this->isLoggedIn = $this->authenticator->isLoggedIn();
    }

    private function handleInput() {
        $loginController = new \Controller\Login($this->loginView, $this->authenticator);

        $registerController = new \Controller\Register($this->registerView, $this->authenticator);

        $loginController->doLogin();
        $loginController->doLogout();
        $registerController->doRegister();
    }

    private function generateOutput() {
        $dateTimeView = new \View\DateTime();
        $layoutView = new \View\Layout($this->isLoggedIn);

        $this->loginView->doHeaders();
        $this->registerView->doHeaders();

        $layoutView->render(
            $this->isLoggedIn,
            $this->loginView,
            $dateTimeView,
            $this->registerView
        );
    }
}