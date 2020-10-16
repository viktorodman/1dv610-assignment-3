<?php 

namespace View\Auth;

require_once('view/auth/Login.php');
require_once('view/auth/Register.php');

class AuthViews {
    private $loginView;
    private $registerView;
    public function __construct(\Authenticator $authenticator) 
    {
        $this->loginView = new \View\Auth\Login($authenticator);
        $this->registerView = new \View\Auth\Register($authenticator);
    }

    public function getLoginView() : \View\Auth\Login {
        return $this->loginView;
    }

    public function getRegisterView() : \View\Auth\Register {
        return $this->registerView;
    }
}