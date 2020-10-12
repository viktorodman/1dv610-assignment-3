<?php

session_start();
require_once(__DIR__ . '/../common/loginModule/src/Authenticator.php' );
require_once('./TempSettings.php');

require_once('controller/MainController.php');

$settings = new \Settings();
$authenticator = new \Authenticator($settings->getDBConnection());

$mainController = new \Controller\MainController($settings, $authenticator);

$mainController->run();



