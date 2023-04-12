<?php

namespace TimeDesign\Controllers;

use TimeDesign\Response\PageResponse;

class AboutController extends Controller {
    public function index(): PageResponse {
        $this->core->setPageTitle("About");
        return new PageResponse($this->core, "About.twig", []);
    }
}