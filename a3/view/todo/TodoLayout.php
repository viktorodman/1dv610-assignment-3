<?php

namespace View\Todo;

require_once('Todo.php');
require_once('TodoList.php');
require_once('TodoForm.php');
require_once('model/Todo.php');
require_once('model/TodoList.php');
require_once('model/TodoInfo.php');

class TodoLayout {
    private static $createURLID = 'create';
    private static $showURL = 'show';
    private static $showAllURLID = 'todos';
    private static $updateURL = 'update';
    private static $deleteURL = 'delete';
    
    private $todoListView;
    private $todoFormView;

    public function __construct(TodoForm $todoFormView, \Model\TodoList $userTodos) {
        $this->todoListView = new TodoList($userTodos);
        $this->todoFormView = $todoFormView;
    }

    public function getTodoLayoutHTML() : string {
        $pageContentHTML = '';

        if ($this->userWantsToShowTodos()) {
            $pageContentHTML = $this->todoListView->generateTodoListHTML();
        } else if($this->userWantToShowTodoForm()) {
            $pageContentHTML = $this->todoFormView->generateTodoFormHTML();
        } 

        return $this->generateLayoutHTML($pageContentHTML);
    }

    
    public function userWantToShowTodoForm() : bool {
        return isset($_GET[self::$createURLID]);
    }
    
    public function userWantsToShowTodos() : bool {
        return isset($_GET[self::$showAllURLID]);
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
}

