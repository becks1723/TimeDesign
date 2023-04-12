<?php

namespace TimeDesign\Controllers;

use TimeDesign\Response\PageResponse;

class ScheduleController extends Controller {
    public function index(): PageResponse {
        $this->core->setPageTitle("Schedule");
        return new PageResponse($this->core, "Schedule.twig", []);
    }
}