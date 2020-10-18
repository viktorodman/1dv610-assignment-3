<?php

namespace View\Todo;

class TodoForm {

    private static $description = 'TodoForm::Description';
    private static $title = 'TodoForm::Title';
    private static $date = 'TodoForm::Date';
    private static $todo = 'TodoForm::Todo';

    public function generateTodoFormHTML() : string {
        return '
        <div class="todoFromWrapperTitle">
        <span>Create a TODO</span>
        </div>
        <div class="todoFormWrapper">
            <form class="todoForm" id="loginForm" method="post" >
                <label for="' . self::$title . '">Title</label>
                <input type="text" id="' . self::$title . '" name="' . self::$title . '" placeholder="Title"/>
                <br>
                <hr>
                <label for="' . self::$description . '">Description</label>
                <textarea class="todoDescription" id="'. self::$description .'" name="'. self::$description .'" rows="4" cols="50" placeholder="Enter the stupid todo description here ...."></textarea>
                <br>
                <hr>
                <label for="' . self::$date . '">Set a todo date</label>
                <br>
                <input type="date" id="'. self::$date .'" name="'. self::$date .'"/>
                <hr>
                <input id="loginSubmit" type="submit" name="' . self::$todo . '" value="Submit Todo" />
            </form>
        </div>
        ';
    }

    public function getRequestTitle() : string {
        return $_POST[self::$title];
    }
    public function getRequestDescription() : string {
        return $_POST[self::$description];
    }
    public function getRequestDate() : string {
        return $_POST[self::$date];
    }

    public function userWantsToAddTodo() : bool {
        return isset($_POST[self::$todo]);
    }
}

