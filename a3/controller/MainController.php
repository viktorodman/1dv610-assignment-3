<?php

namespace Controller;

require_once('view/Layout.php');
require_once('controller/auth/AuthMain.php');
require_once('controller/todo/TodoMain.php');

class MainController {
    private $authenticator;
    private $isLoggedIn;
    private $layoutView;
    private $settings;

    public function __construct(\Settings $settings, \Authenticator $authenticator) {
        $this->settings = $settings;
        $this->authenticator = $authenticator;

        $this->authViews = new \View\Auth\AuthViews($authenticator);
    }   

    public function run() {
        $this->loadState();
        $this->handleInput();
        $this->generateOutput();
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
                $this->authenticator->getSessionUser()
            );

            $todoMainController->run();
        } else {
            $authMainController = new \Controller\Auth\AuthMain($this->authenticator, $this->layoutView);

            $authMainController->run();
        }
    }

    private function generateOutput() {
        
    }
}