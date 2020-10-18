<?php

namespace Model\DAL;

class SessionDAL {
    private $sessionIndex;
    private $sessionIndexWasSetAndShouldNotBeRemovedDuringRequest = false;

    public function __construct(string $sessionIndex) {
        $this->sessionIndex = $sessionIndex;
    }

    public function setIndexValue(string $value) {
        $this->sessionIndex = $value;
        $this->sessionIndexWasSetAndShouldNotBeRemovedDuringRequest = true;
    }

    public function getRememberedSessionVariable() {
		if ($this->sessionIndexWasSetAndShouldNotBeRemovedDuringRequest) {
            return filter_var($_SESSION[$this->sessionIndex], FILTER_SANITIZE_STRING);
        }

        if(isset($_SESSION[$this->sessionIndex])) {
            $message = filter_var($_SESSION[$this->sessionIndex], FILTER_SANITIZE_STRING);
            unset($_SESSION[$this->sessionIndex]);
            return $message;
        }
        return "";
	}
}