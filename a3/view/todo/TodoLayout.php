<?php

namespace View\Todo;

require_once('Todo.php');
require_once('TodoList.php');
require_once('TodoForm.php');
require_once('model/Todo.php');
require_once('model/TodoList.php');
require_once('model/TodoInfo.php');

class TodoLayout {

    private $fakeInfo1;
    private $fakeInfo2;

    private $fakeTodo1;
    private $fakeTodo2;

    private $fakeList;

    private static $createURLID = 'create';
    private static $showURL = 'show';
    private static $showAllURLID = 'todos';
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

        $this->fakeList = new \Model\TodoList(array($this->fakeTodo1, $this->fakeTodo2));
    }

    public function renderTodoLayout() : string {
        $pageContentHTML = '';

        if ($this->userWantsToShowTodos()) {
            $todoListView = new \View\Todo\TodoList();

            $pageContentHTML = $todoListView->generateTodoListHTML($this->fakeList);
        } else if($this->userWantToShowTodoForm()) {
            $todoFormView = new \View\Todo\TodoForm();

            $pageContentHTML = $todoFormView->generateTodoFormHTML();
        } 

        return $this->generateLayoutHTML($pageContentHTML);
    }


    private function generateLayoutHTML(string $pageContentHTML) : string {
        return 
        '
            <div class="todoNavBar">
                <a href="?'. self::$createURLID .'">Create New TODO</a>
                <a href="?'. self::$showAllURLID .'">Show all todos</a>
            </div>
            <div class="sideColumn">
            </div>
            <div class="column">
                '. $pageContentHTML. '
            </div>
            <div class="sideColumn">
            </div>
        ';
    }

    public function userWantToShowTodoForm() : bool {
        return isset($_GET[self::$createURLID]);
    }

    public function userWantsToShowTodos() : bool {
        return isset($_GET[self::$showAllURLID]);
    }
}

