<?php

namespace Model\DAL;

require_once('model/Todo.php');
require_once('model/TodoList.php');
require_once('model/TodoInfo.php');

class TodoDAL {
    private static $tableName = "Todo";
    private static $authorField = "author";
    private static $idField = "todoid";
    private static $titleField = "title";
    private static $descriptionField = "description";
    private static $deadlineField = "deadline";
    private static $createDateField = "createdate";

    private $dbConnection;
   
    public function __construct(\mysqli $dbConnection) {
        $this->dbConnection = $dbConnection;

        $this->createUserTableIfNeeded();
    }


    public function getUsersTodosFromDatabase(string $todoAuthor) : \Model\TodoList {
        $query = "SELECT * FROM " . self::$tableName . " WHERE author=?";
        
        $results = [];

        if($stmt = $this->dbConnection->prepare($query)) {
            $stmt->bind_param("s", $todoAuthor);
            $stmt->execute();
            $test = $stmt->get_result();

            while ($row = $test->fetch_array(MYSQLI_ASSOC)) {
                $todoInfo = new \Model\TodoInfo(
                    $row[self::$titleField],
                    $row[self::$descriptionField],
                    $row[self::$deadlineField],
                    $row[self::$createDateField]
                );

                $results[] = new \Model\Todo(
                    $row[self::$authorField],
                    $todoInfo,
                    $row[self::$idField]
                );
            }
            $stmt->close();
        } else {
            throw new \Exception("Something went wrong when fetching todos from database");
        }
       
        return new \Model\TodoList($results);
    }

    public function addTodoToDatabase(\Model\Todo $todo) {
        
        $query = "INSERT INTO " . self::$tableName . " (author, todoid, title, description, deadline, createdate) VALUES (?, ?, ?, ?, ?, ?)";

        $author = $todo->getAuthor();
        $todoID = $todo->getID();
        $title = $todo->getTitle();
        $description = $todo->getDescription();
        $deadline = $todo->getDeadline();
        $createDate = $todo->getCreateDate();

        if ($stmt = $this->dbConnection->prepare($query)) {
            $stmt->bind_param(
                "ssssss",
                $author,
                $todoID,
                $title,
                $description,
                $deadline,
                $createDate
        );
            $stmt->execute();
            $stmt->close();
        } else {
            throw new \Exception("Something went wrong when trying to add todo to database");
        }
    }

    public function deleteTodo(string $username, string $todoID) {
        $query = 'DELETE FROM '. self::$tableName .' WHERE author=? AND todoid=?';

        if ($stmt = $this->dbConnection->prepare($query)) {
            $stmt->bind_param('ss', $username, $todoID);
            $stmt->execute();
            $stmt->close();
        } else {
            throw new \Exception("Something went wrong when trying to delete todo from database");
        }
    }

    private function createUserTableIfNeeded() {
        $createTable = 'CREATE TABLE IF NOT EXISTS ' . self::$tableName . ' (
            '. self::$authorField .' VARCHAR(30) NOT NULL,
            '. self::$idField .' VARCHAR(60) NOT NULL,
            '. self::$titleField .' VARCHAR(255) NOT NULL,
            '. self::$descriptionField .' TEXT NOT NULL,
            '. self::$deadlineField .' VARCHAR(60) NOT NULL,
            '. self::$createDateField .' VARCHAR(60) NOT NULL
            )';

        if($this->dbConnection->query($createTable)) {
           // TODO Add message
        } else {
            throw new \Exception("Something went wrong when trying to create todo table to database");
        }
    }
} 