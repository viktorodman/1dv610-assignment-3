<?php

namespace View\Auth;

class Login {
    private static $login = 'ViewAuthLogin::Login';
	private static $logout = 'ViewAuthLogin::Logout';
	private static $name = 'ViewAuthLogin::UserName';
	private static $password = 'ViewAuthLogin::Password';
	private static $keep = 'ViewAuthLogin::KeepMeLoggedIn';
	private static $messageId = 'ViewAuthLogin::Message';
	private static $cookieName = 'ViewAuthLogin::CookieName';
	private static $cookiePassword = 'ViewAuthLogin::CookiePassword';
    private static $welcomeMessage = 'Welcome';
	private static $goodByeMessage = 'Bye bye!';
	private static $loginCookieMessage = "Welcome back with cookie";
	private static $loginRemeberMessage = "Welcome and you will be remembered";

	private $sessionHandler;
	private $usernameSessionIndex;
    private $messageSessionIndex;
	private $shouldBeReloaded = false;

    public function __construct(
		\SessionStorageHandler $sessionHandler,
		string $usernameSessionIndex,
        string $messageSessionIndex
	) {
		$this->sessionHandler = $sessionHandler;
		$this->usernameSessionIndex = $usernameSessionIndex;
        $this->messageSessionIndex = $messageSessionIndex;
    }

    public function getLoginFormHTML() : string {
        $remeberedUsername = $this->sessionHandler->getRememberedSessionVariable($this->usernameSessionIndex);
		$message = $this->sessionHandler->getRememberedSessionVariable($this->messageSessionIndex);
        
        return $this->generateLoginFormHTML($message, $remeberedUsername);
    }

    public function doHeaders() {
		if ($this->shouldBeReloaded) {
			header('Location: /a3');
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

		$this->sessionHandler->setSessionVariable($this->usernameSessionIndex, $username);
		$this->sessionHandler->setSessionVariable($this->messageSessionIndex, $message);

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

		$this->sessionHandler->setSessionVariable($this->messageSessionIndex, self::$goodByeMessage);
    }
    
    public function unsetCookies() {
        setcookie(self::$cookieName, '', time()-70000000);
        setcookie(self::$cookiePassword, '', time()-70000000);
	}
	
	public function setUserCookies(string $cookieUsername, string $cookiePassword, int $cookieDuration) {
        setcookie(self::$cookieName, $cookieUsername, $cookieDuration);
        setcookie(self::$cookiePassword, $cookiePassword, $cookieDuration);
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


    private function generateLoginFormHTML(string $message, string $remeberedUsername) : string {
        return '
        <div class="sideColumn">
        </div>
        <div class="column">
            <div id="loginArea">
                <span class="loginTitle" id="">Login</span>
                <form class="authForm" id="loginForm" method="post" > 
                    <p class="errorMessage" id="'. self::$messageId .'">'. $message .'</p>
                    <hr>
                    <label for="' . self::$name . '"></label>
                    <input type="text" id="' . self::$name . '" name="' . self::$name . '" value="'.$remeberedUsername.'" placeholder="Username"/>
                    <label for="' . self::$password . '"></label>
                    <input type="password" id="' . self::$password . '" name="' . self::$password . '" placeholder="Password"/>
                    <div>
                        <label for="' . self::$keep . '">Keep me logged in  :</label>
                        <input type="checkbox" id="' . self::$keep . '" name="' . self::$keep . '" />
                    </div>
                    <div>
                        <input id="loginSubmit" type="submit" name="' . self::$login . '" value="Login" />
                    </div>
                </form>
            </div>
        </div>
        <div class="sideColumn">
        </div>';
    }
}