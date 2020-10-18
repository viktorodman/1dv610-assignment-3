<?php

namespace Controller\Todo;

require_once('model/Todo.php');
require_once('model/TodoList.php');
require_once('model/TodoInfo.php');

class Todo {
    private $todoViews;
    private $todoFormView;
    private $todoDAL;
    private $username;
    private $userTodos;


    public function __construct(
        \View\Todo\TodoViews $todoViews,
        \Model\DAL\TodoDAL $todoDAL, 
        \Model\TodoList $userTodos, 
        string $username
    ) 
    {
        $this->todoViews = $todoViews;
        $this->username = $username;
        $this->todoDAL = $todoDAL;
        $this->userTodos = $userTodos;
    }

    public function doTodos() {
        try {
           $this->showTodo();
           $this->addTodo();
           $this->updateTodo();
           $this->deleteTodo();
        } catch (\Throwable $e) {
            //throw $th;
        }
    }

    private function showTodo() {
        $todoListView = $this->todoViews->getTodoListView();

        if ($todoListView->userWantsToShowTodo()) {
            $todo = $this->userTodos->getTodoByID($todoListView->getRequestTodoId());
            $this->todoViews->getTodoView()->setSelectedTodo($todo);
        }
    }

    private function addTodo() {
        if ($this->todoViews->getTodoFormView()->userWantsToAddTodo()) {            
            $todo = $this->attemptToCreateTodo();
            
            $this->todoDAL->addTodoToDatabase($todo);
        }
    }

    private function updateTodo() {

    }

    private function deleteTodo() {
        $todoView = $this->todoViews->getTodoView();
        if ($todoView->userWantsToDeleteTodo()) {
            $todoID = $todoView->getRequestSelectedTodoID();

            $this->todoDAL->deleteTodo($this->username, $todoID);
        }
    }


    private function attemptToCreateTodo() : \Model\Todo {
        $todoInformation = new \Model\TodoInfo(
            $this->todoFormView->getRequestTitle(),
            $this->todoFormView->getRequestDescription(),
            "Not Started",
            $this->todoFormView->getRequestDate(),
            date('Y-m-d')
        );

        return new \Model\Todo(
            $this->username,
            $todoInformation,
            uniqid()
        );
    }
}