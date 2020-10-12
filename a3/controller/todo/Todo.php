<?php

namespace Controller\Todo;


class Todo {
    private $startView;


    public function __construct(\View\Todo\Start $startView) {
        $this->startView = $startView;
    }

    public function showSelectedTodo() {
        
    }
}