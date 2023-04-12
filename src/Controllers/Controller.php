<?php

namespace TimeDesign\Controllers;

use TimeDesign\Core;
use TimeDesign\Response\JsonResponse;

abstract class Controller {
    protected $core;

    public function __construct(Core $core) {
        $this->core = $core;
    }

    public function jsonResponse($data): JsonResponse {
        return new JsonResponse($this->core, $data);
    }
}
