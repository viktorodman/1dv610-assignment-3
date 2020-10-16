<?php

namespace Model\DAL;

require_once('model/Todo.php');
require_once('model/TodoList.php');
require_once('model/TodoInfo.php');

class TodoDAL {
    private static $tableName = "Todo";
    private static $authorField = "author";
    private static $idField = "id";
    private static $titleField = "title";
    private static $descriptionField = "description";
    private static $deadlineField = "deadline";
    private static $createDateField = "createdate";
    private static $statusField = "status";

    private $dbConnection;
   
    public function __construct(\mysqli $dbConnection) {
        $this->dbConnection = $dbConnection;

        $this->createUserTableIfNeeded();
    }

    public function getUserTodos(string $todoAuthor) : \Model\TodoList {
        $query = "SELECT * FROM " . self::$tableName . " WHERE author=?";
        
        $results = [];

        if($stmt = $this->dbConnection->prepare($query)) {
            $stmt->bind_param("s", $todoAuthor);
            $stmt->execute();

            


            $test = $stmt->get_result();

            
           

            while ($row = $test->fetch_array(MYSQLI_ASSOC)) {
            }



            

            $stmt->close();
        }
       
        return new \Model\TodoList($results);
    }

    public function addTodoToDatabase(\Model\Todo $todo) {
        
        $query = "INSERT INTO " . self::$tableName . " (author, id, title, description, deadline, createdate) VALUES (?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = $this->dbConnection->prepare($query)) {
            $stmt->bind_param(
                "sssssss",
                $todo->getAuthor(),
                $todo->getID(),
                $todo->getTitle(),
                $todo->getDescription(),
                $todo->getDeadline(),
                $todo->getCreateDate(),
                $todo->getStatus()
        );
            $stmt->execute();
            $stmt->close();
        } else {
            // Todo Add error message
        }
    }

    private function createUserTableIfNeeded() {
        $createTable = 'CREATE TABLE IF NOT EXISTS ' . self::$tableName . ' (
            '. self::$authorField .' VARCHAR(30) NOT NULL,
            '. self::$idField .' VARCHAR(60) NOT NULL,
            '. self::$titleField .' VARCHAR(255) NOT NULL,
            '. self::$descriptionField .' TEXT NOT NULL,
            '. self::$deadlineField .' VARCHAR(60) NOT NULL,
            '. self::$createDateField .' VARCHAR(60) NOT NULL,
            '. self::$statusField .' VARCHAR(60) NOT NULL
            )';

        if($this->dbConnection->query($createTable)) {
           // Add message
        } else {
            // Add error message
        }
    }
} 