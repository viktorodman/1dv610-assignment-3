<?php

namespace Controller\Todo;

require_once('view/todo/TodoViews.php');
require_once('view/todo/TodoLayout.php');

require_once('controller/todo/Todo.php');

require_once('model/DAL/TodoDAL.php');
require_once('model/TodoList.php');

class TodoMain {
    private static $sessionFlashMessageIndex = 'sessionFlashMessageIndex';

    private $todoLayoutView;
    private $todoViews;
    private $todoDAL;
    private $userTodos;
    private $username;
    private $layoutView;
    private $sessionHandler;

    public function __construct(\View\Layout $layoutView, 
                                \mysqli $dbConnection,
                                \SessionStorageHandler $sessionHandler,
                                string $username) {
    
        $this->layoutView = $layoutView;
        $this->todoDAL = new \Model\DAL\TodoDAL($dbConnection);
        $this->username = $username;
        $this->sessionHandler = $sessionHandler;
    }

    public function run () {
        $this->loadState();
        $this->handleInput();
        $this->generateOutput();
    }

    private function loadState() {
        $this->userTodos = $this->todoDAL->getUsersTodosFromDatabase($this->username);

        $this->todoViews = new \View\Todo\TodoViews(
            $this->userTodos, 
            $this->sessionHandler, 
            self::$sessionFlashMessageIndex
        );

        $this->todoLayoutView = new \View\Todo\TodoLayout(
            $this->todoViews, 
            self::$sessionFlashMessageIndex, 
            $this->sessionHandler
        );
    }

    private function handleInput() {
        $todoController = new \Controller\Todo\Todo(
            $this->todoViews,
            $this->todoDAL, 
            $this->userTodos, 
            $this->username,
        );
        $todoController->doTodos();
    }

    private function generateOutput() {
        $this->todoViews->doHeaders();
        $this->layoutView->renderLoggedInLayout($this->todoLayoutView);
    }  
}