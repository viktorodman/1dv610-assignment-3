<?php

namespace Model;

class TodoList {
    private $todoList;

    public function __construct(array $todoList) {
        $this->todoList = $todoList;
    }

    public function getTodos() : array {
        return $this->todoList;
    }

    public function isEmpty() : bool {
        return count($this->todoList) < 1;
    }

    public function getTodoByID(string $todoID) : \Model\Todo {
        foreach ($this->todoList as $todo) {
            if ($todo->getID() === $todoID) {
                
                return $todo;
            }            
        }
        throw new \Exception("Todo not found");
    }
}