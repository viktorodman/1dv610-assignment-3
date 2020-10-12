<?php

namespace View\Todo;

require_once('Todo.php');
require_once('model/Todo.php');
require_once('model/TodoInfo.php');

class Start {

    public function getStartHTML() : string {
        return $this->generateStartHTML();
    }



    private function generateStartHTML() : string {
        $todo = new \View\Todo\Todo();
        $todoInfo = new \Model\TodoInfo(
            "Ny titel",
            "Ny beskrivande beskrivning",
            "Den är klar"
        );
        $todoItem = new \Model\Todo(
            "en användare",
            $todoInfo
        );
        $todoInfo = $todo->generateTodoHTML($todoItem);
        return '
            <div class="sideColumn">
            </div>
            <div class="column">
                '. $todoInfo .'
            </div>
            <div class="sideColumn">
            </div>
            '
        ;
    }
}

