<?php

require_once('view/Layout.php');
require_once('view/auth/Login.php');

$loginView = new \View\Auth\Login();
$layoutView = new \View\Layout();

$layoutView->render(false, $loginView);