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
    
    public function __construct(\Authenticator $authenticator, \View\Layout $layoutView) {
        $this->loginView = new \View\Auth\Login($authenticator);
        $this->registerView = new \View\Auth\Register($authenticator);
        $this->layoutView = $layoutView;
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
        $this->layoutView->renderLoggedOutLayout($this->loginView, $this->registerView);
    }   
}