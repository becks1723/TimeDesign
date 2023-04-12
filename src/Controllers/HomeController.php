<?php

namespace TimeDesign\Controllers;

use TimeDesign\Response\JsonResponse;
use TimeDesign\Response\PageResponse;

class HomeController extends Controller {
    public function index(): PageResponse {
        $this->core->addCSSFile('home.css');
        $this->core->addJSFile("home.js");
        return new PageResponse($this->core, "Home.twig", []);
    }

    public function getData(): JsonResponse {
        $courses = $_GET['courses'] ?? null;
        $jobs = $_GET['jobs'] ?? null;
        $clubs = $_GET['clubs'] ?? null;
        $course_ids = [];
        $job_ids = [];
        $club_ids = [];
        if ($courses !== null) {
            foreach ($courses as $c) {
                $course_ids[] = $c['id'];
            }
        }
        if ($jobs !== null) {
            foreach ($jobs as $j) {
                $job_ids[] = $j['id'];
            }
        }
        if ($clubs !== null) {
            foreach ($clubs as $c) {
                $club_ids[] = $c['id'];
            }
        }
        $course_res = $this->core->getDbQueries()->getCourseHours($course_ids);
        $job_res = $this->core->getDbQueries()->getJobHours($job_ids);
        $club_res = $this->core->getDbQueries()->getClubHours($club_ids);
        return new JsonResponse($this->core, [
            'course' => $course_res,
            'job' => $job_res,
            'club' => $club_res
        ]);
    }
}
