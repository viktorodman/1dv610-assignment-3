<?php

namespace Model;

class TodoInfo {
    private $title;
    private $description;
    private $status;

    public function __construct(string $title, string $description, string $status) {
        $this->title = $title;
        $this->description = $description;
        $this->status = $status;
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
}