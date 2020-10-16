<?php

namespace Controller\Todo;

require_once('view/todo/TodoLayout.php');
require_once('controller/todo/Todo.php');
require_once('model/DAL/TodoDAL.php');
require_once('model/TodoList.php');



class TodoMain {
    private $todoLayoutView;
    private $todoFormView;
    private $todoDAL;
    private $userTodos;
    private $username;
    private $layoutView;

    public function __construct(\View\Layout $layoutView, \mysqli $dbConnection, string $username) {
        $this->layoutView = $layoutView;
        $this->todoFormView = new \View\Todo\TodoForm();
        $this->todoDAL = new \Model\DAL\TodoDAL($dbConnection);
        $this->username = $username;
    }

    public function run () {
        $this->loadState();
        $this->handleInput();
        $this->generateOutput();
    }

    private function loadState() {
        $this->userTodos = $this->todoDAL->getUserTodos($this->username);
        $this->todoLayoutView = new \View\Todo\TodoLayout($this->todoFormView, $this->userTodos);
    }

    private function handleInput() {
        $todoController = new \Controller\Todo\Todo($this->todoLayoutView, $this->todoFormView, $this->userTodos);
        $todoController->doTodos();
    }

    private function generateOutput() {
        $this->layoutView->renderLoggedInLayout($this->todoLayoutView);
    }  
}