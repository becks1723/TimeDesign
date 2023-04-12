<?php

namespace TimeDesign\Controllers;

use TimeDesign\Response\JsonResponse;

class JobController extends Controller {
    public function getJob(): JsonResponse {
        if (!isset($_GET['name'])) {
            return $this->jsonResponse([]);
        }
        $jobs = $this->core->getDbQueries()->getJobsWhereNameLike($_GET['name']);
        return $this->jsonResponse($jobs);
    }
}
