<?php

namespace Controller\Auth;

require_once('view/auth/Login.php');
require_once('view/auth/Register.php');

require_once('controller/auth/Login.php');
require_once('controller/auth/Register.php');

class AuthMain {
    private static $rememberedUserSessionIndex = "rememberedUserSessionIndex";
    private static $messageSessionIndex = "messageSessionIndex";

    private $loginView;
    private $registerView;
    private $layoutView;
    private $authenticator;
    private $sessionHandler;
    
    public function __construct(
        \Authenticator $authenticator, \View\Layout $layoutView, \SessionStorageHandler $sessionHandler) {

        $this->loginView = new \View\Auth\Login(
            $sessionHandler, 
            self::$rememberedUserSessionIndex, 
            self::$messageSessionIndex
        );

        $this->registerView = new \View\Auth\Register(
            $sessionHandler,
            self::$rememberedUserSessionIndex, 
            self::$messageSessionIndex
        );

        $this->layoutView = $layoutView;
        $this->authenticator = $authenticator;
        $this->sessionHandler = $sessionHandler;
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