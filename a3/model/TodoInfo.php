<?php

namespace Model;

require_once('model/TodoTitle.php');
require_once('model/TodoDescription.php');

class TodoInfo {
    private $title;
    private $description;
    private $deadLine;
    private $createDate;

    public function __construct(string $title, string $description, string $deadLine, string $createDate) {
        $this->title = new TodoTitle($title);
        $this->description = new TodoDescription($description);
        if (empty($deadLine)) {
            $this->deadLine = "No todo date";
        } else {
            $this->deadLine = $deadLine;
        }
        $this->createDate = $createDate;
    }

    public function getTitle() : string {
        return $this->title->getTitle();
    }

    public function getDescription() : string {
        return $this->description->getDescription();
    }

    public function getDeadline() : string {
        return $this->deadLine;
    }

    public function getCreateDate() : string {
        return $this->createDate;
    }
}