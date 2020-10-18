<?php

class SessionStorageHandler {
    private $sesisonVariableWasSet = false;

    public function __construct() {
        // empty 
    }

    public function setSessionVariable(string $sessionIndex, $value) {
        $_SESSION[$sessionIndex] = $value;
        $this->sesisonVariableWasSet = true;
    }

    public function unsetSessionVariable(string $sessionIndex) {
        unset($_SESSION[$sessionIndex]);
    }

    public function getRememberedSessionVariable(string $variableSessionIndex) {
		if ($this->sesisonVariableWasSet) {
            return filter_var($_SESSION[$variableSessionIndex], FILTER_SANITIZE_STRING);
        }

        if(isset($_SESSION[$variableSessionIndex])) {
            $message = filter_var($_SESSION[$variableSessionIndex], FILTER_SANITIZE_STRING);
            unset($_SESSION[$variableSessionIndex]);
            return $message;
        }
        return "";
	}
}