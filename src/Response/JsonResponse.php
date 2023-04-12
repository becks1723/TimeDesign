<?php

namespace TimeDesign\Response;

use TimeDesign\Core;

class JsonResponse extends Response {
    private $data;

    public function __construct(Core $core, $data) {
        parent::__construct($core);
        $this->data = $data;
    }

    public function render() {
        return json_encode($this->data);
    }
}
