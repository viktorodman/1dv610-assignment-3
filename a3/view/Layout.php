<?php

namespace View;

class Layout {

    private static $registerURLID = 'register';
    private static $logout = 'logout';
    private static $registerText = 'Register a new user';
    private static $goBackText = 'Back to login';
    private static $logoutMessage = 'Bye bye!';
    private $linkText;
    private $navigationURL;

    private $shouldBeReloaded = false;

    private $isLoggedIn; 

    public function __construct(bool $isLoggedIn) {
        $this->isLoggedIn = $isLoggedIn;
    }

    public function renderLoggedOutLayout(\View\Auth\Login $loginView, \View\Auth\Register $registerView) {
       if ($this->shouldShowRegisterForm()) {
            $this->render($registerView->getRegisterFormHTML());
        } else {
            $this->render($loginView->getLoginFormHTML());
        }
    }

    public function renderLoggedInLayout(\View\Todo\TodoLayout $todoLayout) {
        $this->render($todoLayout->getTodoLayoutHTML());
    }

    public function userWantsToLogout() : bool {
        return isset($_POST[self::$logout]);
    }

    public function doHeaders() {
		if ($this->shouldBeReloaded) {
			header('Location: /a3');
		}
    }
    
    public function reloadPageAndLogout() {
		$this->shouldBeReloaded = true;
    }

    private function render(string $pageHTML) {
        echo '<!DOCTYPE html>
                <html>
                    <head>
                        <link rel="stylesheet" href="style.css">
                        <meta charset="utf-8">
                        <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@300&display=swap" rel="stylesheet">
                        <title>Todo App</title>
                    </head>
                    <body>
                    <header></header
                       '. $this->getBodyHTML($pageHTML) .'
                    </body>
                </html>
            ';  
    }

    private function getBodyHTML(string $pageHTML) : string {
        return '
                <div>
                    '. $this->getHeaderHTML() .'
                    <div class="row">
                        '. $pageHTML .'
                    </div>
                    <footer>
                        <p>Footer</p>
                    </footer>
                </div>
            ';
    }

    private function getHeaderHTML() : string {
        return ' <header>
                    <div class="row">
                        <div class="sideColumn">
                        </div> 
                        <div class="column">
                        <span><span id="logo">TODOiN</span></span>
                        </div>
                        <div class="sideColumn">
                        <nav>
                            '. $this->getNavHTML() .'
                        </nav>
                        </div>
                    </div>
                </header>
                ';
    }  

    private function getNavHTML() : string {
        if ($this->isLoggedIn) {
            // THIS WILL BE REPLACED WITH A LOGOUT FORM BUTTON
            return '<form class="logoutForm" method="post" >
                        <input class="logoutButton" type="submit" name="' . self::$logout . '" value="logout"/>
                    </form>';
        } else if ($this->shouldShowRegisterForm()){
            return '<a href="/a3">Back To Login</a>';
        } else {
            return '<a href="?'. self::$registerURLID .'">Register New Account</a>';
        }
    }
    

    private function shouldShowRegisterForm() : bool {
        return isset($_GET[self::$registerURLID]);
    }  
}