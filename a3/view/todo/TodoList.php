<?php

namespace View\Todo;


class TodoList {
    private static $noTodosMessage = "You dont have any todos!";
    private $userTodos;

    public function __construct(\Model\TodoList $userTodos) {
        $this->userTodos = $userTodos;
    }


    public function generateTodoListHTML() : string {
        $userTodos = '';
       
        if ($this->userTodos->isEmpty()) {
            $userTodos = self::$noTodosMessage;
        } else {
            $userTodos = $this->generateListOfTodos($this->userTodos);
        }

        return '
            <div class="todoList">
            <h1>My TODOS</h1>
                '. $userTodos .'            
            </div>
        ';
    }

    private function generateListOfTodos(\Model\TodoList $todos) : string {
        $todoList = '';
        foreach ($todos->getTodos() as $todo) {
            $todoList .= '
                <div class="todoListItem">
                    <span class="todoListItemTitle">'. $todo->getTitle() .'</span>
                    <br>
                    <span class="todoListItemAuthor">'. $todo->getAuthor() .'</span>
                    <span class="todoListItemStatus">'. $todo->getStatus() .'</span>
                </div>
            ';
        }

        return $todoList;
    }

}