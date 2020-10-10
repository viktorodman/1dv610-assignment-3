<?php

namespace View;


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

	private $authenticator;
	private $shouldBeReloaded = false;
	

	public function __construct(\Authenticator $authenticator) {
		$this->authenticator = $authenticator;
	}

	
	/**
	 * Create HTTP response
	 *
	 * Should be called after a login attempt has been determined
	 *
	 * @return  void BUT writes to standard output and cookies!
	 */
	public function response() {
		$remeberedUsername = $this->authenticator->getRemeberedUsername();
		$message = $this->authenticator->getSessionMessage();
		
		if ($this->authenticator->isLoggedIn()) {
			$response = $this->generateLogoutButtonHTML($message);
		} else {
			$response = $this->generateLoginFormHTML($message, $remeberedUsername);
		}

		return $response;
	}

	public function doHeaders() {
		if ($this->shouldBeReloaded) {
			header('Location: /a2');
		}
	}

	public function reloadPageAndLogin() {
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

		$this->authenticator->remeberSuccessfulLogin($username, $message);

		$this->shouldBeReloaded = true;
	}

	public function reloadPageAndLogout() {

		if ($this->userWantsToLoginWithCookies()) {
			$this->unsetCookies();
		}

		$this->authenticator->attemptLogout(self::$goodByeMessage);
		
		$this->shouldBeReloaded = true;
	}

	public function reloadPageAndShowErrorMessage(string $errorMessage) {
		$username = '';
		if ($this->usernameWasSentInRequest()) {
			$username = $this->getRequestUsername();
		}

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$this->shouldBeReloaded = true;
		}

		$this->authenticator->remeberFailedLogin($username, $errorMessage);
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

	public function userWantsToBeRemembered() : bool {
		return isset($_POST[self::$keep]);
	}

	public function userWantsToLogout () : bool {
		return isset($_POST[self::$logout]);
	}

	public function getRequestUsername() : string {
		return $_POST[self::$name];
	}

	public function getRequestPassword() : string {
		return $_POST[self::$password];
	}	

	public function getCookieUsername() : string {
        return $_COOKIE[self::$cookieName];
	}
	
	public function getCookiePassword() : string {
        return $_COOKIE[self::$cookiePassword];
	}

	private function usernameWasSentInRequest() : bool {
		return isset($_POST[self::$name]);
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