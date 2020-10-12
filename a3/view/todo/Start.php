<?php

namespace View\Todo;

class Start {

    public function getStartHTML() : string {
        return $this->generateStartHTML();
    }



    private function generateStartHTML() : string {
        return '
            <div class="column">
            </div>
            <div class="column">
                <h1>DINA TODOISAR</h1>
            </div>
            <div class="column">
            </div>
            '
        ;
    }
}