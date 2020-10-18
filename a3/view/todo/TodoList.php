<?php

namespace View\Todo;


class TodoList {
    private static $noTodosMessage = "You dont have any todos!";
    private static $showURL = 'show';
    private static $idURLField = 'todoid';
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
            <span class="todoListTitle">MY TODOS</span>
                '. $userTodos .'            
            </div>
        ';
    }

    public function userWantsToShowTodo() : bool {
        return isset($_GET[self::$showURL]) and isset($_GET[self::$idURLField]);
    }

    public function getRequestTodoId() : string {
        return $_GET[self::$idURLField];
    }

    private function generateListOfTodos(\Model\TodoList $todos) : string {
        $todoList = '';
        foreach ($todos->getTodos() as $todo) {
            $todoList .= '
            
                <div class="todoListItem">
                    <a href="?'. self::$showURL .'&'. self::$idURLField .'='. $todo->getID() .'">
                        <span class="todoListItemTitle">'. $todo->getTitle() .'</span>
                    </a>
                    <br>
                    <span class="todoListItemCreateDate">Create date: '. $todo->getCreateDate() .'</span>
                    <br>
                    <span class="todoListItemStatus">'. $todo->getStatus() .'</span>
                </div>
            ';
        }

        return $todoList;
    }

}