<?php

namespace PCBW\App\Includes\Abstracts;

use \PCBW\App\Kernel;

abstract class Admin {
    public function __construct( Kernel $kernel ) {
        $this->plugin = $kernel;
    }
    
    abstract public function actions(): void;
}