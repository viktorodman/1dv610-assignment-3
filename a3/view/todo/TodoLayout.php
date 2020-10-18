<?php

namespace View\Todo;


require_once('model/Todo.php');
require_once('model/TodoList.php');
require_once('model/TodoInfo.php');

class TodoLayout {
    private static $createURLID = 'create';
    private static $showURL = 'show';
    private static $showAllURLID = 'todos';
    private static $updateURL = 'update';
    private static $deleteURL = 'delete';
    
    private $todoViews;
    private $sessionFlashMessageIndex;
    private $sessionHandler;

    public function __construct(\View\Todo\TodoViews $todoViews, 
                                string $sessionFlashMessageIndex, 
                                \SessionStorageHandler $sessionHandler) {

        $this->todoViews = $todoViews;
        $this->sessionFlashMessageIndex = $sessionFlashMessageIndex;
        $this->sessionHandler = $sessionHandler;
    }

    public function getTodoLayoutHTML() : string {
        $message = $this->sessionHandler->getRememberedSessionVariable($this->sessionFlashMessageIndex);
        $pageContentHTML = '';

        if ($this->userWantsToShowTodos()) {

            $pageContentHTML = $this->todoViews->getTodoListHTML();

        } else if($this->userWantToShowTodoForm()) {

            $pageContentHTML = $this->todoViews->getTodoFormHTML();

        } else if ($this->todoViews->getTodoListView()->userWantsToShowTodo()) {
            
            $pageContentHTML = $this->todoViews->getTodoView()->generateTodoHTML();
        }

        return $this->generateLayoutHTML($pageContentHTML, $message);
    }
  
    public function userWantToShowTodoForm() : bool {
        return isset($_GET[self::$createURLID]);
    }
    
    public function userWantsToShowTodos() : bool {
        return isset($_GET[self::$showAllURLID]);
    }

    private function generateLayoutHTML(string $pageContentHTML, string $message) : string {

        return 
        '
            <div class="todoNavBar">
                <a href="?'. self::$createURLID .'">Create New TODO</a>
                <a href="?'. self::$showAllURLID .'">Show all todos</a>
            </div>
            '. $this->getFlashMessageBarHTML($message) .'
            <div class="sideColumn">
            </div>
            <div class="column">
                '. $pageContentHTML. '
            </div>
            <div class="sideColumn">
            </div>
        ';
    }

    private function getFlashMessageBarHTML(string $message) : string {
        if (strlen($message) < 1) {
            return '';
        } else {
            return '
                <div class="flashMessageBar">
                    <span>'. $message .'</span>
                </div>
            ';
        }
    }
}

