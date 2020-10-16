<?php

namespace Model;

class TodoInfo {
    private $title;
    private $description;
    private $status;
    private $deadLine;
    private $createDate;

    public function __construct(string $title, string $description, string $status, string $deadLine, string $createDate) {
        $this->title = $title;
        $this->description = $description;
        $this->status = $status;
        $this->deadLine = $deadLine;
        $this->createDate = $createDate;
    }

    public function getTitle() : string {
        return $this->title;
    }

    public function getDescription() : string {
        return $this->description;
    }

    public function getStatus() : string {
        return $this->status;
    }

    public function getDeadline() : string {
        return $this->deadLine;
    }

    public function getCreateDate() : string {
        return $this->createDate;
    }

    // MOVE THIS to create view
    /* private function generateCreateDate() : string {
        date_default_timezone_set('Europe/Stockholm');

        return date('Y-m-d');
    } */
}