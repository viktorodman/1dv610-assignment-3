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
    private $sessionHandler;
    private $usernameSessionIndex;
    private $messageSessionIndex;
 
    public function __construct(
        \SessionStorageHandler $sessionHandler,
        string $usernameSessionIndex,
        string $messageSessionIndex
    ) 
    {
        $this->sessionHandler = $sessionHandler;
        $this->usernameSessionIndex = $usernameSessionIndex;
        $this->messageSessionIndex = $messageSessionIndex;
    }

    public function response() {
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

    private function generateRegisterFormHTML(string $errorMessage, string $remeberedUsername) : string{
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