<?php

namespace PCCW\App\Includes\Abstracts;

use \PCCW\App\Kernel;

abstract class Front {
    public function __construct( Kernel $kernel ) {
        $this->plugin = $kernel;
    }

    abstract public function actions(): void;
}