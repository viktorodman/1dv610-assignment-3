<?php

session_start();

//INCLUDE THE FILES NEEDED...
require_once('View/Login.php');
require_once('View/DateTime.php');
require_once('View/Layout.php');
require_once('View/Register.php');
require_once('controller/Login.php');
require_once('controller/Layout.php');
require_once('controller/Register.php');

require_once('model/DAL/Database.php');
require_once('../Settings.php');
require_once('model/DAL/UserDatabase.php');
require_once('model/DAL/CookieDatabase.php');
require_once('model/DAL/UserSessionStorage.php');
require_once('model/DAL/UserCookieStorage.php');
//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'On');


$db = new \Model\DAL\Database();
$settings = new \Settings();

/* $dbConnection = $db->getConnection(); */

$userdb = new \Model\DAL\UserDatabase($settings);
$cookiedb = new \Model\DAL\CookieDatabase($settings->getDBConnection());

$uss = new \Model\DAL\UserSessionStorage();
$ucs = new \Model\DAL\UserCookieStorage($cookiedb);


//CREATE OBJECTS OF THE VIEWSs
$layoutView = new \View\Layout();
$loginView = new \View\Login($uss, $ucs);
$dateTimeView = new \View\DateTime();
$registerView = new \View\Register($uss);

$registerController = new \Controller\Register($registerView, $userdb);
$loginController = new \Controller\Login($loginView, $userdb);
$layoutController = new \Controller\Layout($layoutView);

//  För att skapa controllers behöver jag RegisterView, LoginView och LayoutView Samt: UserDb

$registerController->doRegister();
$loginController->doLogin();
$loginController->doLogout();
$layoutController->doLayout();

$isLoggedIn = $uss->userSessionIsActive();

$layoutView->render($isLoggedIn, $loginView, $dateTimeView, $registerView);



