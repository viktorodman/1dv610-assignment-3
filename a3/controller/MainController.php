<?php

namespace Controller;

require_once('view/Layout.php');
require_once('view/auth/Login.php');
require_once('view/auth/Register.php');
require_once('view/todo/TodoLayout.php');

require_once('controller/auth/Login.php');
require_once('controller/auth/Register.php');

class MainController {
    private $authenticator;
    private $loginView;
    private $registerView;
    private $startView;

    public function __construct(\Settings $settings, \Authenticator $authenticator) {
        $this->settings = $settings;
        $this->authenticator = $authenticator;

        $this->loginView = new \View\Auth\Login($authenticator);
        $this->registerView = new \View\Auth\Register($authenticator);
        $this->startView = new \View\Todo\TodoLayout();
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
        $loginController = new \Controller\Auth\Login($this->loginView, $this->authenticator);

        $registerController = new \Controller\Auth\Register($this->registerView, $this->authenticator);

        $loginController->doLogin();
        // NEED TO CHECK FOR LOGOUT
        $registerController->doRegister();
    }

    private function generateOutput() {
        $layoutView = new \View\Layout();

        $this->loginView->doHeaders();
        $this->registerView->doHeaders();

        $layoutView->render(
            $this->isLoggedIn,
            $this->loginView,
            $this->registerView,
            $this->startView
        );
    }
}