<?php

namespace Model;


class Todo {
    private $id;
    private $author;
    private $todoInfo;
    
    public function __construct(string $author, \Model\TodoInfo $todoInfo) {
        $this->author = $author;
        $this->todoInfo = $todoInfo;
        $this->id = $this->generateID();
    }

    public function getAuthor() : string {
        return $this->author;
    }
    
    public function getTitle() : string {
        return $this->todoInfo->getTitle();
    }
    public function getDescription() : string {
        return $this->todoInfo->getDescription();
    }
    public function getStatus() : string {
        return $this->todoInfo->getStatus();
    }

    public function getDeadline() : string {
        return $this->todoInfo->getDeadline();
    }

    public function getCreateDate() : string {
        return $this->todoInfo->getCreateDate();
    }

    public function getID() : int {
        return $this->id;
    }

    private function generateID() : string {
        return uniqid();
    } 
}