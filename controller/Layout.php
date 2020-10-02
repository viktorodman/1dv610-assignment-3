<?php

namespace Controller;

class Layout {

    private $layoutView;

    public function __construct(\View\Layout $layoutView) {
        $this->layoutView = $layoutView;
    }

    public function doLayout() {
        if($this->layoutView->shouldShowRegisterForm()) {
            $this->layoutView->showRegisterForm();
        }
    }

}