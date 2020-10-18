<?php

namespace Model;


class Todo {
    private $id;
    private $author;
    private $todoInfo;
    
    public function __construct(string $author, \Model\TodoInfo $todoInfo, string $id) {
        $this->author = $author;
        $this->todoInfo = $todoInfo;
        $this->id = $id;
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

    public function getDeadline() : string {
        return $this->todoInfo->getDeadline();
    }

    public function getCreateDate() : string {
        return $this->todoInfo->getCreateDate();
    }

    public function getID() : string {
        return $this->id;
    }
}