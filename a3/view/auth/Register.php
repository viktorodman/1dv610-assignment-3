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
    private $sessionHandler;
    private $usernameSessionIndex;
    private $messageSessionIndex;
 
    public function __construct(
        \SessionStorageHandler $sessionHandler,
        string $usernameSessionIndex,
        string $messageSessionIndex
    ) {
        $this->sessionHandler = $sessionHandler;
        $this->usernameSessionIndex = $usernameSessionIndex;
        $this->messageSessionIndex = $messageSessionIndex;
    }

    public function getRegisterFormHTML() : string {
        $remeberedUsername = $this->sessionHandler->getRememberedSessionVariable($this->usernameSessionIndex);
		$message = $this->sessionHandler->getRememberedSessionVariable($this->messageSessionIndex);
        
        return $this->generateRegisterFormHTML($message, $remeberedUsername);
    }

    public function doHeaders() {
		if ($this->shouldBeReloaded) {
			header($this->reloadURL);
		}
    }
    
    public function reloadPageAndRemeberRegisteredAccount() {
        $this->sessionHandler->setSessionVariable(
            $this->usernameSessionIndex,
            $this->getRequestUsername()
        );

        $this->sessionHandler->setSessionVariable(
            $this->messageSessionIndex,
            self::$registeredUserMessage
        );

        $this->shouldBeReloaded = true;
        $this->reloadURL = self::$indexURL;
    }


    public function reloadPageAndShowErrorMessage(string $errorMessage) {
        $this->sessionHandler->setSessionVariable(
            $this->usernameSessionIndex,
            $this->getRequestUsername()
        );

        $this->sessionHandler->setSessionVariable(
            $this->messageSessionIndex,
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
            <span class="loginTitle">Register</span>
            <form class="authForm" action="?' . self::$registerURLID .'" method="post" enctype="multipart/form-data">
                    <p class="errorMessage" id="'. self::$messageID .'">'. $errorMessage .'</p>
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