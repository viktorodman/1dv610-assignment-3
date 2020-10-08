<?php

namespace Controller;

class Controller {

    private $settings;
    private $userSession;
    private $usersDAL;
    private $userCookieDAL;
    private $isLoggedIn;

    private $loginView;

    public function __construct(\Settings $settings) {
        $this->settings = $settings;

        $this->userSession = new \Model\DAL\UserSession();
        $this->usersDAL = new \Model\DAL\UsersDAL($settings);
        $this->userCookieDAL = new \Model\DAL\UserCookieDAL($settings);

        $this->loginView = new \View\Login($this->userSession);
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
        //  För att skapa controllers behöver jag RegisterView, LoginView och LayoutView Samt: UserDb
        $loginController = new \Controller\Login($this->loginView, $this->usersDAL, $this->userCookieDAL);

        $loginController->doLogin();
        $loginController->doLogout();
    }

    private function generateOutput() {
        $dateTimeView = new \View\DateTime();

    }
}