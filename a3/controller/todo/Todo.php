<?php

namespace Controller\Todo;

require_once('model/Todo.php');
require_once('model/TodoList.php');
require_once('model/TodoInfo.php');

class Todo {
    private $todoView;
    private $todoFormView;
    
    


    public function __construct(\View\Todo\TodoLayout $todoLayout, \View\Todo\TodoForm $todoFormView, \Model\TodoList $userTodos) {
        $this->todoView = $todoLayout;
        $this->todoFormView = $todoFormView;
    }

    public function doTodos() {
        
        try {
            if ($this->todoView->userWantsToShowTodos()) {
                $this->listTodos();
            }
        } catch (\Throwable $e) {
            //throw $th;
        }
    }

    private function showTodo() {
        
    }

    private function listTodos() {
        
    }

    private function updateTodo() {

    }

    private function deleteTodo() {
        
    }
}