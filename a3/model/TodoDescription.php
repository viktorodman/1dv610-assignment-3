<?php

namespace Model;

class TodoDescription {
    private static $descriptionToShortMessage = "Description to short! Description needs to be at least 1 character";

    private $description;

    public function __construct(string $description) {
        if (strlen($description) < 1) {
            throw new \Exception(self::$descriptionToShortMessage);
        }

        $this->description = $description;
    }

    public function getDescription() : string {
        return $this->description;
    }
}