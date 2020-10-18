<?php

namespace Model\DAL;

class SessionDAL {

    private $sessionIndexWasSetAndShouldNotBeRemovedDuringRequest = false;

    public function setIndexValue(string $sessionIndex, string $value) {
        $_SESSION[$sessionIndex] = $value;
        $this->sessionIndexWasSetAndShouldNotBeRemovedDuringRequest = true;
    }

    public function getRememberedSessionVariable(string $sessionIndex) {
		if ($this->sessionIndexWasSetAndShouldNotBeRemovedDuringRequest) {
            return filter_var($_SESSION[$sessionIndex], FILTER_SANITIZE_STRING);
        }

        if(isset($_SESSION[$sessionIndex])) {
            $message = filter_var($_SESSION[$sessionIndex], FILTER_SANITIZE_STRING);
            unset($_SESSION[$sessionIndex]);
            return $message;
        }
        return "";
	}
}