<?php

namespace Controller;

require_once('model/DAL/UserSession.php');
require_once('model/DAL/UsersDAL.php');
require_once('model/DAL/UserCookieDAL.php');
require_once('controller/Login.php');
require_once('controller/Register.php');
require_once('View/Layout.php');
require_once('View/Login.php');
require_once('View/Register.php');
require_once('View/DateTime.php');

class LoginApp {

    private $settings;
    private $userSession;
    private $usersDAL;
    private $userCookieDAL;
    private $isLoggedIn;

    private $loginView;
    private $registerView;

    public function __construct(\Settings $settings) {
        $this->settings = $settings;

        $this->userSession = new \Model\DAL\UserSession();
        $this->usersDAL = new \Model\DAL\UsersDAL($settings);
        $this->userCookieDAL = new \Model\DAL\UserCookieDAL($settings);

        $this->loginView = new \View\Login($this->userSession);
        $this->registerView = new \View\Register($this->userSession);
    }

    public function run() {
        $this->loadState();

        $this->handleInput();

        $this->generateOutput();
    }

    private function loadState() {
       $this->isLoggedIn = $this->userSession->userSessionIsActive();
    }

    private function handleInput() {
        $loginController = new \Controller\Login(
            $this->loginView,
            $this->usersDAL,
            $this->userCookieDAL
        );

        $registerController = new \Controller\Register(
            $this->registerView,
            $this->usersDAL
        );

        $loginController->doLogin();
        $loginController->doLogout();
        $registerController->doRegister();
    }

    private function generateOutput() {
        $dateTimeView = new \View\DateTime();
        $layoutView = new \View\Layout();

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