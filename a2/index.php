<?php

session_start();

//INCLUDE THE FILES NEEDED...

require_once('./Settings.php');
require_once('controller/LoginApp.php');
/* require_once('../common/loginModule/src/Authenticator.php'); */
/* require_once(dirname(__FILE__) . '/../common/loginModule/src/Authenticator.php'); */
/* require_once(__DIR__ . '/../common/loginModule/src/Authenticator.php' ); */

var_dump( realpath( __DIR__ . '/../common/loginModule/src/Authenticator.php' ) );



$settings = new \Settings();

$authenticator = new \Authenticator($settings->getDBConnection());

$loginApp = new \Controller\LoginApp($settings, $authenticator);

$loginApp->run();
