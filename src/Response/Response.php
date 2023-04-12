<?php

namespace TimeDesign\Response;

use TimeDesign\Core;

abstract class Response {
    protected $core;

    public function __construct(Core $core) {
        $this->core = $core;
    }

    public abstract function render();
}