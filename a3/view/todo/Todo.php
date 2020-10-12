<?php

namespace View\Todo;

class Todo {

    
    public function generateTodoHTML(\Model\Todo $todo) : string {
        return '
            <div class="todo">
                <hr>
                <div class="todoStatus">
                    <span>Status: </span><span>'. $todo->getStatus() .'</span> Added: <span>DatumTid</span>
                </div>
                <hr>
                <div class="todoTitle">
                    <span>'. $todo->getTitle() .'</span>
                </div>
                <hr>
                <div class="todoDescription">
                    <span>'. $todo->getDescription() .'</span>
                </div>
                <hr>
            </div>';
    }
}