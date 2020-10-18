<?php

namespace Model;

class TodoTitle {
    private static $titleToShortMessage = "Title to short! Must be at least 1 character";
    private static $titleToLongMessage = "Title to long! Must be less than 30 characters";
    private static $titleMinLength = 1;
    private static $titleMaxLength = 30;
    private $title;

    public function __construct(string $title) {
        if (strlen($title) < self::$titleMinLength) {
            throw new \Exception(self::$titleToShortMessage);
        }
        
        if (strlen($title) > self::$titleMaxLength) {
            throw new \Exception(self::$titleToLongMessage);
        }
        $this->title = $title;
    }

    public function getTitle() : string {
        return $this->title;
    }
}