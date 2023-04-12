<?php

namespace TimeDesign\Controllers;

use TimeDesign\Response\JsonResponse;
use TimeDesign\Response\PageResponse;
use TimeDesign\Response\RedirectResponse;

class InputController extends Controller {

    public function index(): PageResponse {
        $this->core->setPageTitle("Input");
        $this->core->addCSSFile("input.css");
        $this->core->addJSFile("input.js");
        $courses = $this->core->getDbQueries()->getCoursesWhereNameLike("");
        usort($courses, function ($a, $b) {
            return strcmp($a["school_course_id"], $b["school_course_id"]);
        });
        $jobs = $this->core->getDbQueries()->getJobsWhereNameLike("");
        usort($jobs, function ($a, $b) {
            return strcmp($a["company"], $b["company"]);
        });
        $clubs = $this->core->getDbQueries()->getClubsWhereNameLike("");
        usort($clubs, function ($a, $b) {
            return strcmp($a["name"], $b["name"]);
        });
        return new PageResponse($this->core, "Input.twig", [
            "courses" => $courses,
            "jobs" => $jobs,
            "clubs" => $clubs
        ]);
    }

    public function newCourse(): JsonResponse {
        if (empty($_POST['name']) || !is_string($_POST['name'])) {
            return new JsonResponse($this->core, ['status' => 'fail']);
        }
        if (empty($_POST['courseId']) || !is_string($_POST['courseId'])) {
            return new JsonResponse($this->core, ['status' => 'fail']);
        }
        $this->core->getDbQueries()->createCourse($_POST['name'], $_POST['courseId']);
        return new JsonResponse($this->core, ['status' => 'success']);
    }

    public function newJob(): JsonResponse {
        if (empty($_POST['name']) || !is_string($_POST['name'])) {
            return new JsonResponse($this->core, ['status' => 'fail']);
        }
        if (empty($_POST['companyName']) || !is_string($_POST['companyName'])) {
            return new JsonResponse($this->core, ['status' => 'fail']);
        }
        $this->core->getDbQueries()->createJob($_POST['name'], $_POST['companyName']);
        return new JsonResponse($this->core, ['status' => 'success']);
    }

    public function newClub(): JsonResponse {
        if (empty($_POST['name']) || !is_string($_POST['name'])) {
            return new JsonResponse($this->core, ['status' => 'fail']);
        }
        $this->core->getDbQueries()->createClub($_POST['name']);
        return new JsonResponse($this->core, ['status' => 'success']);
    }

    public function submitCourseReview() {
        $required_fields = ['courseId', 'professor', 'lecHours', 'hwHours', 'studyHours'];

        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                return new RedirectResponse($this->core, "/TimeDesign/input");
            }
        }

        $this->core->getDbQueries()->addCourseReview(
            $_POST['courseId'],
            $this->core->getLoggedInUser()->getId(),
            $_POST['professor'],
            $_POST['lecHours'],
            $_POST['hwHours'],
            $_POST['studyHours'],
            empty($_POST['difficulty']) ? null : $_POST['difficulty'],
            empty($_POST['comments']) ? null : $_POST['comments']
        );

        return new RedirectResponse($this->core, "/TimeDesign/input");
    }

    public function submitJobReview() {
        $required_fields = ['jobId', 'jobType', 'hours'];

        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                return new RedirectResponse($this->core, "/TimeDesign/input");
            }
        }

        $this->core->getDbQueries()->addJobReview(
            $_POST['jobId'],
            $this->core->getLoggedInUser()->getId(),
            $_POST['jobType'],
            $_POST['hours']
        );

        return new RedirectResponse($this->core, "/TimeDesign/input");
    }

    public function submitClubReview() {
        $required_fields = ['clubId', 'role', 'hours'];

        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                return new RedirectResponse($this->core, "/TimeDesign/input");
            }
        }

        $this->core->getDbQueries()->addClubReview(
            $_POST['clubId'],
            $this->core->getLoggedInUser()->getId(),
            $_POST['role'],
            $_POST['hours']
        );

        return new RedirectResponse($this->core, "/TimeDesign/input");
    }

}
