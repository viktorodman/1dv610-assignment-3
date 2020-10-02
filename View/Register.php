<?php

namespace View;

require_once('model/Username.php');
require_once('model/Password.php');
require_once('model/Credentials.php');
require_once('Login.php');

class Register {
    private static $registerURLID = 'register';
    private static $messageID = 'RegisterView::Message';
	private static $password = 'RegisterView::Password';
	private static $passwordRepeat = 'RegisterView::PasswordRepeat';
    private static $name = 'RegisterView::UserName';
    private static $register = 'RegisterView::Register';
    private static $passwordToShortMessage = 'Password has too few characters, at least 6 characters.';
    private static $usernameToShortMessage = 'Username has too few characters, at least 3 characters.';
    private static $passwordDoesNotMatchMessage = 'Passwords do not match.';
    private static $registeredUserMessage = 'Registered new user.';

    private $userSessionStorage;
 
    public function __construct(\Model\DAL\UserSessionStorage $userSessionStorage) {
        $this->userSessionStorage = $userSessionStorage;
    }

    public function response() {
        $remeberedUsername = $this->userSessionStorage->getRememberedUsername();
        $errorMessage = $this->userSessionStorage->getSessionMessage();
        
        return $this->generateRegisterFormHTML($errorMessage, $remeberedUsername);
    }

    public function userWantsToRegister() : bool {
        return isset($_POST[self::$register]);
    }

    public function getRegisterCredentials() {
        if(strlen($_POST[self::$name]) < 3 and strlen($_POST[self::$password]) < 6) {
            throw new \Exception(self::$usernameToShortMessage . '<br>' . self::$passwordToShortMessage);
        }

        return new \Model\Credentials($this->getUsername(), $this->getPassword());
    }

    public function reloadPageAndNotifyRegisteredAccount() {
        $this->userSessionStorage->setSessionMessage(self::$registeredUserMessage);
		$this->userSessionStorage->setRemeberedUsername($_POST[self::$name]);

        $this->userSessionStorage->setUsernameToBeRemembered();
        $this->userSessionStorage->setMessageToBeViewed();

        header('Location: /');
    }


    public function reloadPageAndShowErrorMessage(string $errorMessage) {
		$this->userSessionStorage->setSessionMessage($errorMessage);
		$this->userSessionStorage->setRemeberedUsername($_POST[self::$name]);

		$this->userSessionStorage->setMessageToBeViewed();
		$this->userSessionStorage->setUsernameToBeRemembered();
		
		header('Location: /?register');
	}

    private function getUsername() : \Model\Username {
        if (strlen($_POST[self::$name]) < 3) {
            throw new \Exception(self::$usernameToShortMessage);
        }
        return new \Model\Username($_POST[self::$name]);
    }


    private function getPassword() : \Model\Password {
        if (strlen($_POST[self::$password]) < 6) {
            throw new \Exception(self::$passwordToShortMessage);
        }
        if ($_POST[self::$password] !== $_POST[self::$passwordRepeat]) {
            throw new \Exception(self::$passwordDoesNotMatchMessage);
        }

        return new \Model\Password($_POST[self::$password]);
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