<?php

namespace View;

require_once('model/Username.php');
require_once('model/Password.php');
require_once('model/Credentials.php');

class Login {
	private static $login = 'LoginView::Login';
	private static $logout = 'LoginView::Logout';
	private static $name = 'LoginView::UserName';
	private static $password = 'LoginView::Password';
	private static $keep = 'LoginView::KeepMeLoggedIn';
	private static $messageId = 'LoginView::Message';
	private static $usernameField = 'LoginView::LoginField';
	private static $errorMessageNoUsername = 'Username is missing';
	private static $errorMessageNoPassword = 'Password is missing';
	private static $welcomeMessage = 'Welcome';
	private static $goodByeMessage = 'Bye bye!';
	private static $loginCookieMessage = "Welcome back with cookie";

	private $userSessionStorage;
	private $userCookieStorage;
	

	public function __construct(\Model\DAL\UserSessionStorage $userSessionStorage, \Model\DAL\UserCookieStorage $userCookieStorage) {
		$this->userSessionStorage = $userSessionStorage;
		$this->userCookieStorage = $userCookieStorage;
	}

	
	/**
	 * Create HTTP response
	 *
	 * Should be called after a login attempt has been determined
	 *
	 * @return  void BUT writes to standard output and cookies!
	 */
	public function response() {
		$remeberedUsername = $this->userSessionStorage->getRememberedUsername();
		$message = $this->userSessionStorage->getSessionMessage();
		$response;

		if ($this->userHasActiveSession()) {
			$response = $this->generateLogoutButtonHTML($message);
		} else {
			$response = $this->generateLoginFormHTML($message, $remeberedUsername);
		}
		
		
		return $response;
	}

	public function reloadPageAndLogin() {
		$this->userSessionStorage->setSessionMessage(self::$welcomeMessage);
		$this->userSessionStorage->setSessionUser(self::$name);

		$this->userSessionStorage->setMessageToBeViewed();

		header('Location: /');
	}


	public function reloadPageAndLogout() {
		$this->userSessionStorage->setSessionMessage(self::$goodByeMessage);
		$this->userSessionStorage->removeUserSession();

		if ($this->userCookieStorage->userWantsToLoginWithCookies()) {
			$this->userCookieStorage->unsetCookies();
		}

		$this->userSessionStorage->setMessageToBeViewed();
		
		header('Location: /');
	}

	public function reloadPageAndLoginWithCookie() {
		$username;

		$this->userSessionStorage->setSessionMessage(self::$loginCookieMessage);
		if (isset($_POST[self::$name])) {
			$username = $_POST[self::$name];
		} else {
			$username = $this->userCookieStorage->getCookieUsername();
		}

		$this->userSessionStorage->setSessionUser($username);

		$this->userSessionStorage->setMessageToBeViewed();

		header('Location: /');
	}


	public function reloadPageAndShowErrorMessage(string $errorMessage) {
		$this->userSessionStorage->setSessionMessage($errorMessage);
		
		if (isset($_POST[self::$name])) {
			$this->userSessionStorage->setRemeberedUsername($_POST[self::$name]);
			$this->userSessionStorage->setUsernameToBeRemembered();
			
		}

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			header('Location: /');
		}
		

		$this->userSessionStorage->setMessageToBeViewed();
		
	}

	public function userWantsToBeRemembered() : bool {
		return isset($_POST[self::$keep]);
	}

	public function userWantsToLogin () : bool {
		return isset($_POST[self::$login]);
	}

	public function userWantsToLogout () : bool {
		return isset($_POST[self::$logout]);
	}

	public function userWantsToLoginWithCookies() : bool {
		return $this->userCookieStorage->userWantsToLoginWithCookies();
	}

	public function getRequestUserCredentials() : \Model\Credentials {
		return new \Model\Credentials($this->getRequestUsername(), $this->getRequestPassword());
	}

	public function userHasActiveSession() : bool {
		return $this->userSessionStorage->userSessionIsActive();
	}

	public function validateCookies() {
		$this->userCookieStorage->validateUserCookies();
	}

	public function createUserCookie() {
		$this->userCookieStorage->createUserCookies($_POST[self::$name]);
	}

	
	
	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	private function generateLogoutButtonHTML($message) {
		return '
			<form  method="post" >
				<p id="' . self::$messageId . '">' . $message .'</p>
				<input type="submit" name="' . self::$logout . '" value="logout"/>
			</form>
		';
	}
	
	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	private function generateLoginFormHTML($message, $remeberedUsername) {
		return '
			<form method="post" > 
				<fieldset>
					<legend>Login - enter Username and password</legend>
					<p id="'. self::$messageId .'">'. $message .'</p>
					<label for="' . self::$name . '">Username :</label>
					<input type="text" id="' . self::$name . '" name="' . self::$name . '" value="'. $remeberedUsername .'" />
					<label for="' . self::$password . '">Password :</label>
					<input type="password" id="' . self::$password . '" name="' . self::$password . '" />
					<label for="' . self::$keep . '">Keep me logged in  :</label>
					<input type="checkbox" id="' . self::$keep . '" name="' . self::$keep . '" />
					<input type="submit" name="' . self::$login . '" value="login" />
				</fieldset>
			</form>
		';
	}

	
	
	private function getRequestUsername() : \Model\Username {
		if (empty($_POST[self::$name])) {
            throw new \Exception(self::$errorMessageNoUsername);
		}
		
		return new \Model\Username($_POST[self::$name]);
	}

	private function getRequestPassword() : \Model\Password {
		if (empty($_POST[self::$password])) {
            throw new \Exception(self::$errorMessageNoPassword);
		}
		
		return new \Model\Password($_POST[self::$password]);
	}
	
}