<?php

session_start();

require_once(__DIR__ . '/../common/loginModule/src/Authenticator.php' );
require_once(__DIR__ . '/../common/sessionModule/SessionStorageHandler.php');
require_once('./TempSettings.php');

require_once('controller/MainController.php');

$settings = new \Settings();
$sessionHandler = new \SessionStorageHandler();
$authenticator = new \Authenticator($settings->getDBConnection());

$mainController = new \Controller\MainController($settings, $authenticator, $sessionHandler);

$mainController->run();



