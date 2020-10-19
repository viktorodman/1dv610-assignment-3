<?php

namespace Controller;

require_once('view/Layout.php');
require_once('view/Error.php');
require_once('controller/auth/AuthMain.php');
require_once('controller/todo/TodoMain.php');


class MainController {
    private $authenticator;
    private $isLoggedIn;
    private $layoutView;
    private $settings;
    private $sessionHandler;

    public function __construct(\Settings $settings, 
                                \Authenticator $authenticator,
                                \SessionStorageHandler $sessionHandler) {
        
        $this->settings = $settings;
        $this->authenticator = $authenticator;
        $this->sessionHandler = $sessionHandler;
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
       $this->layoutView = new \View\Layout($this->isLoggedIn);
    }

    private function handleInput() {
        if ($this->isLoggedIn) {
            

            $todoMainController = new \Controller\Todo\TodoMain(
                $this->layoutView,
                $this->settings->getDBConnection(),
                $this->sessionHandler,
                $this->authenticator->getUser()
            );
            $todoMainController->run();

            if ($this->layoutView->userWantsToLogout()) {
                $this->authenticator->attemptLogout();
                $this->layoutView->reloadPageAndLogout();
            }

            
        } else {
            $authMainController = new \Controller\Auth\AuthMain(
                $this->authenticator, 
                $this->layoutView,
                $this->sessionHandler
            );

            $authMainController->run();
        }
    }

    private function generateOutput() {
        $this->layoutView->doHeaders();
    }

    private function handleError($e) {
        $errorView = new \View\Error($e, $this->settings);

        $errorView->writeToLog();
        $errorView->echoHTML();
    }
}