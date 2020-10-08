<?php

use Controller\LoginApp;

session_start();

//INCLUDE THE FILES NEEDED...

require_once('./Settings.php');
require_once('controller/LoginApp.php');

$settings = new \Settings();

$loginApp = new \Controller\LoginApp($settings);

$loginApp->run();





