<?php

namespace View;

class Layout {

    private static $registerURLID = 'register';
    private static $registerText = 'Register a new user';
    private static $goBackText = 'Back to login';
    private $linkText;
    private $navigationURL;

    public function render(bool $isLoggedIn, $loginView, $registerView, $startView) {

        if ($isLoggedIn) {
            $correctForm = $startView->getStartHTML();
        }else if ($this->shouldShowRegisterForm()) {
            $correctForm = $registerView->getRegisterFormHTML();
        } else {
          $correctForm = $loginView->getLoginFormHTML();
        }

        echo '<!DOCTYPE html>
      <html>
        <head>
            <link rel="stylesheet" href="style.css">
            <meta charset="utf-8">
            <link href="https://fonts.googleapis.com/css2?family=Oswald&display=swap" rel="stylesheet">
            <title>Login Example</title>
        </head>
        <body>
            <div>
                <header>
                    <div class="row">
                        <div class="sideColumn">
                        </div> 
                        <div class="column">
                        <span>Ey what you <span id="logo">TODOiN</span></span>
                        </div>
                        <div class="sideColumn">
                        <nav>
                            '. $this->renderNavItems($isLoggedIn) .'
                        </nav>
                        </div>
                    </div>
                </header>
            <div class="row">
                '. $correctForm .'
            </div>
            <footer>
                <p>Footer</p>
            </footer>
            </div>
         </body>
      </html>
    ';
    }

    private function shouldShowRegisterForm() : bool {
        return isset($_GET[self::$registerURLID]);
    }
  

    private function renderNavItems(bool $isLoggedIn) : string {
        if ($isLoggedIn) {
            // THIS WILL BE REPLACED WITH A LOGOUT FORM BUTTON
            return '<a href="/a3">Logout</a>';
        } else if ($this->shouldShowRegisterForm()){
            return '<a href="/a3">Back To Login</a>';
        } else {
            return '<a href="?'. self::$registerURLID .'">Register New Account</a>';
        }
    }

}