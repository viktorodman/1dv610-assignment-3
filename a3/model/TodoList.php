<?php

namespace Model;

class TodoList {
    private $todoList;

    public function __construct(array $todoList) {
        $this->todoList = $todoList;
    }

    public function getTodoList() : array {
        return $this->todoList;
    }
}