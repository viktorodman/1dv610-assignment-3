<?php

namespace Controller\Todo;


class Todo {
    private $todoView;


    public function __construct(\View\Todo\TodoLayout $todoLayout) {
        $this->todoView = $todoLayout;
    }

    public function doShowTodo() {
        
    }

    public function doListTodos() {

    }

    public function doUpdateTodo() {

    }

    public function doDeleteTodo() {
        
    }
}