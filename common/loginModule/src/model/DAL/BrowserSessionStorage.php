<?php

namespace Model\DAL;


class BrowserSessionStorage {
    private static $browserSessionIndex = "browserSessionIndex";


    public function setSessionBrowser() {
        $_SESSION[self::$browserSessionIndex] = $_SERVER['HTTP_USER_AGENT'];
    }

    public function getSessionBrowser() {
        return $_SESSION[self::$browserSessionIndex];
    }

    public function unsetSessionBrowser() {
        unset($_SESSION[self::$browserSessionIndex]);
    }

    public function sessionBrowserIsUpToDate() : bool {
        return $_SESSION[self::$browserSessionIndex] == $_SERVER['HTTP_USER_AGENT'];
    }
}