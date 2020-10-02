<?php

namespace Model\DAL;

class UserCookieStorage {
    private static $cookieName = 'LoginView::CookieName';
	private static $cookiePassword = 'LoginView::CookiePassword';
    private static $secondsInAMonth = 3600 * 24 * 30;
    private $cookieDB;

    public function __construct(\Model\DAL\CookieDatabase $cookieDB) {
        $this->cookieDB = $cookieDB;
    }

    public function createUserCookies(string $username) {
        $cookiePassword = $this->generateCookiePasswordString();
        $cookieDuration = $this->getCookieDuration();

        $this->setUserCookies($username, $cookiePassword, $cookieDuration);

        $this->cookieDB->saveCookieInformation($username, $cookiePassword, $cookieDuration);
    }

    public function validateUserCookies() {
        if($this->cookieDB->cookiesAreValid($_COOKIE[self::$cookieName], $_COOKIE[self::$cookiePassword])) {
            $newPassword = $this->generateCookiePasswordString();
            $cookieDuration = $this->getCookieDuration();

            $this->setUserCookies($_COOKIE[self::$cookieName], $newPassword, $cookieDuration);
            $this->cookieDB->updateAndSaveCookieInfo($_COOKIE[self::$cookieName], $newPassword, $cookieDuration);
        } else {
            throw new \Exception("Wrong information in cookies");
        }
    }

    public function userWantsToLoginWithCookies() : bool {
        return isset($_COOKIE[self::$cookieName]) && isset($_COOKIE[self::$cookiePassword]);
    }

    public function getCookieUsername() : string {
        return $_COOKIE[self::$cookieName];
    }

    public function unsetCookies() {
        setcookie(self::$cookieName, '', time()-70000000);
        setcookie(self::$cookiePassword, '', time()-70000000);
    }

    private function setUserCookies(string $username, string $password, int $duration) {
        setcookie(self::$cookieName, $username, $duration);
        setcookie(self::$cookiePassword, $password, $duration);
    }

    private function getCookieDuration() {
        return time() + self::$secondsInAMonth;
    }

    private function generateCookiePasswordString() : string {
        return bin2hex(random_bytes(20));
    }
}