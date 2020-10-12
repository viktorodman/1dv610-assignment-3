<?php

namespace View\Auth;

class Register {
    private static $registerURLID = 'register';
    private static $messageID = 'ViewAuthRegister::Message';
	private static $password = 'ViewAuthRegister::Password';
	private static $passwordRepeat = 'ViewAuthRegister::PasswordRepeat';
    private static $name = 'ViewAuthRegister::UserName';
    private static $register = 'ViewAuthRegister::Register';
    private static $registeredUserMessage = 'Registered new user.';
    private static $registerUserURL = 'Location: /a3?register';
    private static $indexURL = 'Location: /a3';

    private $shouldBeReloaded = false;
    private $reloadURL;
    private $authenticator;
 
    public function __construct(\Authenticator $authenticator) {
        $this->authenticator = $authenticator;
    }

    public function getRegisterFormHTML() : string {
        $remeberedUsername = $this->authenticator->getRemeberedUsername();
        $errorMessage = $this->authenticator->getSessionMessage();
        
        return $this->generateRegisterFormHTML($errorMessage, $remeberedUsername);
    }

    public function doHeaders() {
		if ($this->shouldBeReloaded) {
			header($this->reloadURL);
		}
    }
    
    public function reloadPageAndRemeberRegisteredAccount() {
        $this->authenticator->remeberSuccessfullRegistration(
            $this->getRequestUsername(),
            self::$registeredUserMessage
        );

        $this->shouldBeReloaded = true;
        $this->reloadURL = self::$indexURL;
    }


    public function reloadPageAndShowErrorMessage(string $errorMessage) {
        $this->authenticator->remeberFailedRegistration(
            $this->getRequestUsername(),
            $errorMessage
        );
		
		$this->shouldBeReloaded = true;
        $this->reloadURL = self::$registerUserURL;
	}

    public function userWantsToRegister() : bool {
        return isset($_POST[self::$register]);
    }

    public function getRequestUsername() : string{
        return $_POST[self::$name];
    }


    public function getRequestPassword() : string {
        return $_POST[self::$password];
    }

    public function getRequestRepeatedPassword() : string {
        return $_POST[self::$passwordRepeat];
    }

    private function generateRegisterFormHTML(string $errorMessage, string $remeberedUsername) : string {
        return '
        <div class="sideColumn">
        </div>
        <div class="column">
            <div id="loginArea">
            <h2>Register new user</h2>
            <form class="authForm" action="?' . self::$registerURLID .'" method="post" enctype="multipart/form-data">
                    <p id="'. self::$messageID .'">'. $errorMessage .'</p>
                    <input type="text" size="20" name="'. self::$name .'" id="'. self::$name .'" value="'. $remeberedUsername .'" placeholder="Username"/>
                    <input type="password" size="20" name="'. self::$password .'" id="'. self::$password .'" value="" placeholder="Password"/>
                    <input type="password" size="20" name="'. self::$passwordRepeat .'" id="'. self::$passwordRepeat .'" value="" placeholder="Repeat Password"/>
                    <input id="submit" type="submit" name="'. self::$register .'"  value="Register" />
                    <br/>
            </form>
            </div>
        </div>
        <div class="sideColumn">
        </div>';
    }
}