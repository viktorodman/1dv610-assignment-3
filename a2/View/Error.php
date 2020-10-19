<?php

namespace View;

class Error {
    private $exception;

    public function __construct(\Exception $error, \Settings $settings) {
        $this->exception = $error;
        $this->settings = $settings;
    }

    public function writeToLog() {
        error_log(
            "\n ************ " . date("F j, Y, g:i a") 
            . " \n Error when loading data: " 
            . $this->exception, 3, $this->settings->getErrorLogFileName()
    );	
    }
    
    public function echoHTML() {
		http_response_code();
		echo "sorry something went wrong! " ;
	}
}