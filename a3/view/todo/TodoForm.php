<?php

namespace View\Todo;


class TodoForm {
    private static $messageSessionIndex = "View\\Todo\\TodoForm::messageSessionIndex";
    private static $todoCreateSuccessMessage = "Todo was created!";
    private static $description = 'TodoForm::Description';
    private static $title = 'TodoForm::Title';
    private static $date = 'TodoForm::Date';
    private static $todo = 'TodoForm::Todo';
    private static $todosURL = 'a3?todos';
    private static $createURL = 'a3?create';

    private $sessionHandler;
    private $sessionFlashMessageIndex;
    private $shouldBeReloaded = false;
    private $reloadPageURL;

    public function __construct(\SessionStorageHandler $sessionHandler, string $sessionFlashMessageIndex) {
        $this->sessionHandler = $sessionHandler;
        $this->sessionFlashMessageIndex = $sessionFlashMessageIndex;
    }

    public function generateTodoFormHTML() : string {
        $errorMessage = $this->sessionHandler->getRememberedSessionVariable(self::$messageSessionIndex);

        return '
            <div class="todoFromWrapperTitle">
            <span>Create a TODO</span>
            </div>
            <div class="todoFormWrapper">
                <span class="errorMessage">'. $errorMessage .'</span>
                <form class="todoForm" id="loginForm" method="post" >
                    <label for="' . self::$title . '">Title</label>
                    <br>
                    <span class="todoFormInfo">Max 30 characters</span>
                    <input type="text" id="' . self::$title . '" name="' . self::$title . '" placeholder="Title"/>
                    <br>
                    <hr>
                    <label for="' . self::$description . '">Description</label>
                    <textarea class="todoDescription" id="'. self::$description .'" name="'. self::$description .'" rows="4" cols="50" placeholder="Enter the stupid todo description here ...."></textarea>
                    <br>
                    <hr>
                    <label for="' . self::$date . '">Set a todo date</label>
                    <br>
                    <input type="date" id="'. self::$date .'" name="'. self::$date .'"/>
                    <hr>
                    <input id="loginSubmit" type="submit" name="' . self::$todo . '" value="Submit Todo" />
                </form>
            </div>
        ';
    }

    public function doHeaders() {
		if ($this->shouldBeReloaded) {
			header('Location: /'. $this->reloadPageURL .'');
		}
    }

    public function redirectAndShowCreateMessage() {
        $this->sessionHandler->setSessionVariable($this->sessionFlashMessageIndex, self::$todoCreateSuccessMessage);
        $this->reloadPageURL = self::$todosURL;
        $this->shouldBeReloaded = true;
    }

    public function reloadPageAndShowErrorMessage(string $errorMessage) {
        $this->reloadPageURL = self::$createURL;
        $this->shouldBeReloaded = true;

        $this->sessionHandler->setSessionVariable(self::$messageSessionIndex, $errorMessage);
    }

    public function getRequestTitle() : string {
        return $_POST[self::$title];
    }

    public function getRequestDescription() : string {
        return $_POST[self::$description];
    }

    public function getRequestDate() : string {
        return $_POST[self::$date];
    }

    public function userWantsToAddTodo() : bool {
        return isset($_POST[self::$todo]);
    }
}

