<?php

session_start();

//INCLUDE THE FILES NEEDED...

require_once('./Settings.php');
require_once('controller/LoginApp.php');
require_once('../common/loginModule/src/Authenticator.php');

$settings = new \Settings();

$authenticator = new \Authenticator($settings->getDBConnection());

$loginApp = new \Controller\LoginApp($settings, $authenticator);

$loginApp->run();
