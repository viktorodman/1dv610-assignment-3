<?php

namespace View\Todo;

require_once('Todo.php');
require_once('TodoList.php');
require_once('TodoForm.php');

class TodoViews {
    private $todoView;
    private $todoForm;
    private $todoList;
    private $userTodos;

    public function __construct(\Model\TodoList $userTodos, \SessionStorageHandler $sessionStorageHandler) {
        $this->todoView = new \View\Todo\Todo();
        $this->todoForm = new \View\Todo\TodoForm($sessionStorageHandler);
        $this->todoList = new \View\Todo\TodoList($userTodos);
    }

    public function doHeaders() {
        $this->todoView->doHeaders();
        $this->todoForm->doHeaders();
    }

    public function getTodoView() : \View\Todo\Todo {
        return $this->todoView;
    }

    public function getTodoFormView() : \View\Todo\TodoForm {
        return $this->todoForm;
    }

    public function getTodoListView() : \View\Todo\TodoList {
        return $this->todoList;
    }

    public function getTodoListHTML() : string {
        return $this->todoList->generateTodoListHTML();
    }

    public function getTodoFormHTML() : string {
        return $this->todoForm->generateTodoFormHTML();
    }
}