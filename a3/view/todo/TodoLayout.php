<?php

namespace View\Todo;

require_once('Todo.php');
require_once('model/Todo.php');
require_once('model/TodoInfo.php');

class TodoLayout {

    private $fakeInfo1;
    private $fakeInfo2;

    private $fakeTodo1;
    private $fakeTodo2;

    private $fakeList;

    private static $createURL = 'create';
    private static $showURL = 'show';
    private static $updateURL = 'update';
    private static $deleteURL = 'delete';

    public function __construct() {
        $this->fakeInfo1 = new \Model\TodoInfo(
            "Ny titel 1",
            "Ny beskrivande beskrivning 1",
            "Den är klar 1"
        );
        $this->fakeInfo2 = new \Model\TodoInfo(
            "Ny titel 2",
            "Ny beskrivande beskrivning 2",
            "Den är klar 2"
        );

        $this->fakeTodo1 = new \Model\Todo(
            "Användare",
            $this->fakeInfo1
        );
        $this->fakeTodo2 = new \Model\Todo(
            "Användare",
            $this->fakeInfo2
        );

        $this->fakeList = array($this->fakeTodo1, $this->fakeTodo2);
    }

    public function renderTodoLayout() : string {
        /*TODO:^) 
        * 1. KOLLA VILKEN TYP AV SIDA SOM SKA VISAS
        *       Show 1
        *       Show List
        *       Create
        * 2. Fixa en navbar för TODOS Med items create och show all
        */

        return 
        '
            <div class="sideColumn">
            </div>
            <div class="column">

            </div>
            <div class="sideColumn">
            </div>
        ';
    }
}

