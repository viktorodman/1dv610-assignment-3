<?php

namespace View\Todo;


class TodoList {


    public function generateTodoListHTML(\Model\TodoList $todoList) : string {
        $userTodos = '';

        foreach ($todoList->getTodos() as $todo) {
            $userTodos .= '
                <div class="todoListItem">
                    <span class="todoListItemTitle">'. $todo->getTitle() .'</span>
                    <br>
                    <span class="todoListItemAuthor">'. $todo->getAuthor() .'</span>
                    <span class="todoListItemStatus">'. $todo->getStatus() .'</span>
                </div>
            ';
        }      

        return '
            
            <div class="todoList">
            <h1>My TODOS</h1>
                '. $userTodos .'            
            </div>
        ';
    }

}