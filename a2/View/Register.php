<?php

namespace View;

class Register {
    private static $registerURLID = 'register';
    private static $messageID = 'RegisterView::Message';
	private static $password = 'RegisterView::Password';
	private static $passwordRepeat = 'RegisterView::PasswordRepeat';
    private static $name = 'RegisterView::UserName';
    private static $register = 'RegisterView::Register';
    private static $registeredUserMessage = 'Registered new user.';
    private static $registerUserURL = 'Location: /a2?register';
    private static $indexURL = 'Location: /a2';

    private $shouldBeReloaded = false;
    private $reloadURL;
    private $authenticator;
 
    public function __construct(\Authenticator $authenticator) {
        $this->authenticator = $authenticator;
    }

    public function response() {
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

    private function generateRegisterFormHTML($errorMessage, $remeberedUsername) {
        return '
        <h2>Register new user</h2>
        <form action="?' . self::$registerURLID .'" method="post" enctype="multipart/form-data">
            <fieldset>
            <legend>Register a new user - Write username and password</legend>
                <p id="'. self::$messageID .'">' . $errorMessage . '</p>
                <label for="'. self::$name .'" >Username :</label>
                <input type="text" size="20" name="'. self::$name .'" id="'. self::$name .'" value="'. $remeberedUsername .'" />
                <br/>
                <label for="'. self::$password .'" >Password  :</label>
                <input type="password" size="20" name="'. self::$password .'" id="'. self::$password .'" value="" />
                <br/>
                <label for="'. self::$passwordRepeat .'" >Repeat password  :</label>
                <input type="password" size="20" name="'. self::$passwordRepeat .'" id="'. self::$passwordRepeat .'" value="" />
                <br/>
                <input id="submit" type="submit" name="'. self::$register .'"  value="Register" />
                <br/>
            </fieldset>
        </form>';
    }
}