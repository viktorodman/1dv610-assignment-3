<?php

session_start();

//INCLUDE THE FILES NEEDED...

require_once('./Settings.php');
require_once('controller/LoginApp.php');
/* require_once('../common/loginModule/src/Authenticator.php'); */
/* require_once(dirname(__FILE__) . '/../common/loginModule/src/Authenticator.php'); */
require_once(__DIR__ . '/../common/loginModule/src/Authenticator.php' );
require_once(__DIR__ . '/../common/sessionModule/SessionStorageHandler.php');




$settings = new \Settings();

$authenticator = new \Authenticator($settings->getDBConnection());
$sessionHandler = new \SessionStorageHandler();

$loginApp = new \Controller\LoginApp($authenticator, $sessionHandler, $settings);

$loginApp->run();
