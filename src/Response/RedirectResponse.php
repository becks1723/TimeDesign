<?php

namespace TimeDesign\Response;

use TimeDesign\Core;

class RedirectResponse extends Response {

    private $url;

    public function __construct(Core $core, string $url) {
        parent::__construct($core);
        $this->url = $url;
    }

    public function render() {
        header("Location: $this->url");
        die();
    }
}