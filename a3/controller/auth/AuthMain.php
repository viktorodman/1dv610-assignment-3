<?php

namespace Controller\Auth;

require_once('view/auth/Login.php');
require_once('view/auth/Register.php');

require_once('controller/auth/Login.php');
require_once('controller/auth/Register.php');

class AuthMain {
    private $loginView;
    private $registerView;
    private $layoutView;
    private $authenticator;
    
    public function __construct(\Authenticator $authenticator, \View\Layout $layoutView) {
        $this->loginView = new \View\Auth\Login($authenticator);
        $this->registerView = new \View\Auth\Register($authenticator);
        $this->layoutView = $layoutView;
        $this->authenticator = $authenticator;
    }

    public function run () {
        $this->loadState();
        $this->handleInput();
        $this->generateOutput();
    }

    private function loadState() {
        
    }

    private function handleInput() {
        $loginController = new \Controller\Auth\Login($this->loginView, $this->authenticator);
        $registerController = new \Controller\Auth\Register($this->registerView, $this->authenticator);

        $loginController->doLogin();
        $registerController->doRegister();
    }

    private function generateOutput() {
        $this->loginView->doHeaders();
        $this->registerView->doHeaders();
        $this->layoutView->renderLoggedOutLayout($this->loginView, $this->registerView);
    }   
}