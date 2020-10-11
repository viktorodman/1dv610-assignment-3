<?php

namespace View;

class Layout {

    public function render(bool $isLoggedIn, $loginView) {
        echo '<!DOCTYPE html>
      <html>
        <head>
            <link rel="stylesheet" href="style.css">
            <meta charset="utf-8">
            <link href="https://fonts.googleapis.com/css2?family=Oswald&display=swap" rel="stylesheet">
            <title>Login Example</title>
        </head>
        <body>
            <div class="header">
                <span>Ey what you <span id="logo">TODOiN</span></span>
            </div>
            <div class="topnav">
                '. $this->renderNavItems($isLoggedIn) .'
            </div>
            <div class="row">
                '. $loginView->render() .'
            </div>
         </body>
      </html>
    ';
    }

    private function renderNavItems(bool $isLoggedIn) : string {
        if ($isLoggedIn) {
            return '<a href="#">Logout</a>';
        } else {
            return '<a href="#">Register</a>';
        }
    }

}