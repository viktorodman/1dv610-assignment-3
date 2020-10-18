<?php

namespace View\Todo;

class Todo {
    private static $todoDeletedMessage = "Todo was deleted!";
    private static $delete = 'TodoView::Delete';
    private static $selectedTodoID = 'TodoView::SelectedTodoID';
    private $selectedTodo;
    private $shouldBeReloaded = false;

    private $sessionFlashMessageIndex;
    private $sessionHandler;

    public function __construct(\SessionStorageHandler $sessionHandler, string $sessionFlashMessageIndex) {
        $this->sessionFlashMessageIndex = $sessionFlashMessageIndex;
        $this->sessionHandler = $sessionHandler;
    }
    
    public function generateTodoHTML() : string {
        return '
            <div class="todo">
                <div class="todoShowTitle">
                    <span>'. $this->selectedTodo->getTitle() .'</span>
                </div>
                <hr>
                <div class="todoListItemCreateDate">
                    <span>Create date: '. $this->selectedTodo->getCreateDate() .'</span>
                </div>
                <hr>
                <div class="todoShowDescription">
                    <span >'. $this->selectedTodo->getDescription() .'</span>
                </div>
                <hr>
                <div class="todoListItemCreateDate">
                    <span>Todo Date: '. $this->selectedTodo->getDeadline() .'</span>
                </div>
                <hr>
                '. $this->getDeleteButtonHTML() .'
            </div>
        ';
    }

    public function doHeaders() {
		if ($this->shouldBeReloaded) {
			header('Location: /a3?todos');
		}
    }
    
    public function redirectAndDeleteTodo() {
        $this->sessionHandler->setSessionVariable($this->sessionFlashMessageIndex, self::$todoDeletedMessage);
        $this->shouldBeReloaded = true;
    }

    public function setSelectedTodo(\Model\Todo $toBeDisplayed) {
        $this->selectedTodo = $toBeDisplayed;
    }

    public function getRequestSelectedTodoID() : string {
        return $_POST[self::$selectedTodoID];
    }

    public function userWantsToDeleteTodo() : bool {
        return isset($_POST[self::$delete]);
    }

    private function getDeleteButtonHTML() : string {
        return '
            <div>
                <form class="todoForm" id="loginForm" method="post">
                    <input type="hidden" name='. self::$selectedTodoID .' value="'. $this->selectedTodo->getID() .'">
                    <input id="loginSubmit" type="submit" name="' . self::$delete . '" value="Delete todo" />
                </form>
            </div>
        ';
    }
}