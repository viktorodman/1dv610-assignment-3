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
        $todoFormView = $this->todoViews->getTodoFormView();
        if ($todoFormView->userWantsToAddTodo()) {  
            try {
                $todo = $this->attemptToCreateTodo();
                $this->todoDAL->addTodoToDatabase($todo);
                $todoFormView->redirectAndShowCreateMessage();
            } catch (\Throwable $e) {
                var_dump($e->getMessage());
                exit;
            }          
        }
    }

    private function updateTodo() {

    }

    private function deleteTodo() {
        $todoView = $this->todoViews->getTodoView();
        if ($todoView->userWantsToDeleteTodo()) {
            $todoID = $todoView->getRequestSelectedTodoID();

            $this->todoDAL->deleteTodo($this->username, $todoID);
            $todoView->redirectAndDeleteTodo();
        }
    }


    private function attemptToCreateTodo() : \Model\Todo {
        $todoFormView = $this->todoViews->getTodoFormView();
        $todoInformation = new \Model\TodoInfo(
            $todoFormView->getRequestTitle(),
            $todoFormView->getRequestDescription(),
            $todoFormView->getRequestDate(),
            date('Y-m-d')
        );

        return new \Model\Todo(
            $this->username,
            $todoInformation,
            uniqid()
        );
    }
}