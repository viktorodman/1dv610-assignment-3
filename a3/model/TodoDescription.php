<?php

namespace Model;

require_once('TodoException.php');

class TodoDescription {
    private static $descriptionToShortMessage = "Description to short! Description needs to be at least 1 character";

    private $description;

    public function __construct(string $description) {
        if (strlen($description) < 1) {
            throw new \Model\TodoException(self::$descriptionToShortMessage);
        }

        $this->description = $description;
    }

    public function getDescription() : string {
        return $this->description;
    }
}