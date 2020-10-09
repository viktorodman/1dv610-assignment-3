<?php

namespace View;

require_once('model/RegisterCredentials.php');

class Register {
    private static $registerURLID = 'register';
    private static $messageID = 'RegisterView::Message';
	private static $password = 'RegisterView::Password';
	private static $passwordRepeat = 'RegisterView::PasswordRepeat';
    private static $name = 'RegisterView::UserName';
    private static $register = 'RegisterView::Register';
    private static $registeredUserMessage = 'Registered new user.';
    private static $registerUserURL = 'Location: /?register';
    private static $indexURL = 'Location: /';

    private $shouldBeReloaded = false;
    private $reloadURL;
    private $userSession;
 
    public function __construct(\Model\DAL\UserSession $userSession) {
        $this->userSession = $userSession;
    }

    public function response() {
        $remeberedUsername = $this->userSession->getRememberedUsername();
        $errorMessage = $this->userSession->getSessionMessage();
        
        return $this->generateRegisterFormHTML($errorMessage, $remeberedUsername);
    }

    public function doHeaders() {
		if ($this->shouldBeReloaded) {
			header($this->reloadURL);
		}
    }
    
    public function reloadPageAndNotifyRegisteredAccount() {
        $this->userSession->setSessionMessage(self::$registeredUserMessage);
		$this->userSession->setRemeberedUsername($this->getRequestUsername());

        $this->userSession->setUsernameToBeRemembered();
        $this->userSession->setMessageToBeViewed();

        $this->shouldBeReloaded = true;
        $this->reloadURL = self::$indexURL;
    }


    public function reloadPageAndShowErrorMessage(string $errorMessage) {
		$this->userSession->setSessionMessage($errorMessage);
		$this->userSession->setRemeberedUsername($this->getRequestUsername());

		$this->userSession->setMessageToBeViewed();
		$this->userSession->setUsernameToBeRemembered();
		
		$this->shouldBeReloaded = true;
        $this->reloadURL = self::$registerUserURL;
	}

    public function userWantsToRegister() : bool {
        return isset($_POST[self::$register]);
    }

    public function getRegisterCredentials() : \Model\RegisterCredentials {
        return new \Model\RegisterCredentials(
            $this->getRequestUsername(),
            $this->getRequestPassword(),
            $this->getRequestRepeatedPassword()
        );
    }

    private function getRequestUsername() : string{
        return $_POST[self::$name];
    }


    private function getRequestPassword() : string {
        return $_POST[self::$password];
    }

    private function getRequestRepeatedPassword() : string {
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