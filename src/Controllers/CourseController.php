<?php

namespace TimeDesign\Controllers;

use TimeDesign\Response\JsonResponse;

class CourseController extends Controller {
    public function getCourse(): JsonResponse {
        if (!isset($_GET['name'])) {
            return $this->jsonResponse([]);
        }
        $courses = $this->core->getDbQueries()->getCoursesWhereNameLike($_GET['name']);
        return $this->jsonResponse($courses);
    }
}
