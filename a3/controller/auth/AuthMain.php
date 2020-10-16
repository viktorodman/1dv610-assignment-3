<?php

namespace Controller\Auth;

require_once('view/auth/AuthViews.php');

require_once('controller/auth/Login.php');
require_once('controller/auth/Register.php');

class AuthMain {
    private $authViews;
    private $layoutView;
    
    public function __construct(\Authenticator $authenticator, \View\Layout $layoutView) {
        $this->authViews = new \View\Auth\AuthViews($authenticator);
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
        $loginController = new \Controller\Auth\Login($this->authViews->getLoginView(), $this->authenticator);
        $registerController = new \Controller\Auth\Register($this->authViews->getRegisterView(), $this->authenticator);

        $loginController->doLogin();
        $registerController->doRegister();
    }

    private function generateOutput() {
        $this->layoutView->renderLoggedOutLayout($this->authViews);
    }   
}