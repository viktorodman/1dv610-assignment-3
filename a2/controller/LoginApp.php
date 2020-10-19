<?php

namespace Controller;

require_once('controller/Login.php');
require_once('controller/Register.php');
require_once('View/Layout.php');
require_once('View/Login.php');
require_once('View/Register.php');
require_once('View/DateTime.php');
require_once('View/Error.php');

class LoginApp {
    private static $rememberedUserSessionIndex = "rememberedUserSessionIndex";
    private static $messageSessionIndex = "messageSessionIndex";

    private $authenticator;
    private $isLoggedIn;
    private $sessionHandler;
    private $loginView;
    private $registerView;
    private $settings;

    public function __construct(\Authenticator $authenticator, 
                                \SessionStorageHandler $sessionHandler,
                                \Settings $settings) {
        
        $this->authenticator = $authenticator;
        $this->sessionHandler = $sessionHandler;
        $this->settings = $settings;

        $this->loginView = new \View\Login(
            $sessionHandler, 
            $authenticator->isLoggedIn(),
            self::$rememberedUserSessionIndex,
            self::$messageSessionIndex
        );
        
        $this->registerView = new \View\Register(
            $sessionHandler,
            self::$rememberedUserSessionIndex,
            self::$messageSessionIndex
        );
    }

    public function run() {
        try {
            $this->loadState();

            $this->handleInput();
    
            $this->generateOutput();
        } catch (\Throwable $e) {
            $this->handleError($e);
        }
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

    private function handleError($e) {
        $errorView = new \View\Error($e, $this->settings);

        $errorView->writeToLog();
        $errorView->echoHTML();
    }
}