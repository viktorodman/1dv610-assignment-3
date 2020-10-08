<?php

namespace View;


require_once('model/Credentials.php');

class Login {
	private static $login = 'LoginView::Login';
	private static $logout = 'LoginView::Logout';
	private static $name = 'LoginView::UserName';
	private static $password = 'LoginView::Password';
	private static $keep = 'LoginView::KeepMeLoggedIn';
	private static $messageId = 'LoginView::Message';
	private static $cookieName = 'LoginView::CookieName';
	private static $cookiePassword = 'LoginView::CookiePassword';
	private static $welcomeMessage = 'Welcome';
	private static $goodByeMessage = 'Bye bye!';
	private static $loginCookieMessage = "Welcome back with cookie";
	private static $loginRemeberMessage = "Welcome and you will be remembered";

	private $userSessionStorage;
	private $shouldBeReloaded = false;
	

	public function __construct(\Model\DAL\UserSession $userSessionStorage) {
		$this->userSessionStorage = $userSessionStorage;
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
		
		if ($this->userHasActiveSession()) {
			$response = $this->generateLogoutButtonHTML($message);
		} else {
			$response = $this->generateLoginFormHTML($message, $remeberedUsername);
		}

		return $response;
	}

	public function doHeaders() {
		if ($this->shouldBeReloaded) {
			header('Location: /');
		}
	}

	public function reloadPageAndLogin() {
		$message = '';
		$username = '';

		if ($this->userWantsToBeRemembered()) {
			$message = self::$loginRemeberMessage;
			$username = $this->getRequestUsername();
		} else if ($this->userWantsToLoginWithCookies()) {
			$message = self::$loginCookieMessage;
			$username = $this->getCookieUsername();
		} else {
			$message = self::$welcomeMessage;
			$username = $this->getRequestUsername();
		}

		$this->userSessionStorage->setSessionMessage($message);
		$this->userSessionStorage->setSessionUser($username);
		$this->userSessionStorage->setMessageToBeViewed();

		$this->shouldBeReloaded = true;
	}

	public function reloadPageAndLogout() {
		$this->userSessionStorage->setSessionMessage(self::$goodByeMessage);
		$this->userSessionStorage->removeUserSession();

		if ($this->userCookieStorage->userWantsToLoginWithCookies()) {
			$this->userCookieStorage->unsetCookies();
		}

		$this->userSessionStorage->setMessageToBeViewed();
		
		$this->shouldBeReloaded = true;
	}

	public function reloadPageAndShowErrorMessage(string $errorMessage) {
		$this->userSessionStorage->setSessionMessage($errorMessage);
		
		if ($this->usernameWasSentInRequest()) {
			$this->userSessionStorage->setRemeberedUsername($this->getRequestUsername());
			$this->userSessionStorage->setUsernameToBeRemembered();
			
		}

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$this->shouldBeReloaded = true;
		}
		

		$this->userSessionStorage->setMessageToBeViewed();
	}

	public function unsetCookies() {
        setcookie(self::$cookieName, '', time()-70000000);
        setcookie(self::$cookiePassword, '', time()-70000000);
	}
	
	public function setUserCookies(\Model\UserCookie $userCookie) {
        setcookie(self::$cookieName, $userCookie->getCookieUsername(), $userCookie->getCookieDuration());
        setcookie(self::$cookiePassword, $userCookie->getCookiePassword(), $userCookie->getCookieDuration());
	}

	public function userWantsToLogin () : bool {
		return isset($_POST[self::$login]);
	}

	public function userWantsToLoginWithCookies() : bool {
		return isset($_COOKIE[self::$cookieName]) && isset($_COOKIE[self::$cookiePassword]);
	}

	public function userWantsToLogout () : bool {
		return isset($_POST[self::$logout]);
	}

	public function userWantsToBeRemembered() : bool {
		return isset($_POST[self::$keep]);
	}

	public function getRequestUserCredentials() : \Model\Credentials {
		$username = new \Model\Username($this->getRequestUsername());
		$password = new \Model\Password($this->getRequestPassword());
		return new \Model\Credentials($username, $password);
	}

	public function userHasActiveSession() : bool {
		return $this->userSessionStorage->userSessionIsActive();
	}
	
	public function getCookieUsername() : string {
        return $_COOKIE[self::$cookieName];
	}
	
	public function getCookiePassword() : string {
        return $_COOKIE[self::$cookiePassword];
	}

	private function getUsername() : \Model\Username {
		$requestUsername = $this->getRequestUsername();

		if (empty($requestUsername)) {
            throw new \Exception(self::$errorMessageNoUsername);
		}
		
		return new \Model\Username($requestUsername);
	}

	private function getPassword() : \Model\Password {
		$requestPassword = $this->getRequestPassword();

		if (empty($requestPassword)) {
            throw new \Exception(self::$errorMessageNoPassword);
		}
		
		return new \Model\Password($requestPassword);
	}

	private function usernameWasSentInRequest() : bool {
		return isset($_POST[self::$name]);
	} 

	private function getRequestUsername() : string {
		return $_POST[self::$name];
	}

	private function getRequestPassword() : string {
		return $_POST[self::$password];
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
}