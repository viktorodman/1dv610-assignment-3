<?php

namespace Model;

class UserCookie {
    private static $secondsInAMonth = 3600 * 24 * 30;

    private $cookieUsername;
    private $cookiePassword;
    private $cookieDuration;

    public function __construct(string $cookieUsername) {
        $this->cookieUsername = $cookieUsername;
        $this->cookiePassword = $this->generateCookiePasswordString();
        $this->cookieDuration = $this->generateCookieDuration();
    }

    public function getCookieUsername() : string {
        return $this->cookieUsername;
    }
    public function getCookiePassword() : string {
        return $this->cookiePassword;
    }
    public function getCookieDuration() : int {
        return $this->cookieDuration;
    }

    private function generateCookiePasswordString() : string {
        return bin2hex(random_bytes(20));
    }

    private function generateCookieDuration() : int {
        return time() + self::$secondsInAMonth;
    }
}