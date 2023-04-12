<?php

namespace TimeDesign\Controllers;

use TimeDesign\Response\PageResponse;

class SearchController extends Controller {

    public function index(): PageResponse {
        $courses = [];
        $jobs = [];
        $clubs = [];
        if (isset($_GET['query'])) {
            $query = $_GET['query'];
            $courses = $this->core->getDbQueries()->getCoursesWithAvgHoursWhereNameLike($query);
            $jobs = $this->core->getDbQueries()->getJobsWithAvgHoursWhereNameLike($query);
            $clubs = $this->core->getDbQueries()->getClubsWithAvgHoursWhereNameLike($query);
        }
        $this->core->setPageTitle("Search");
        $this->core->addCSSFile('search.css');
        return new PageResponse($this->core, "Search.twig", [
            'courses' => $courses,
            'jobs' => $jobs,
            'clubs' => $clubs,
            'query' => $_GET['query'] ?? ''
        ]);
    }

}
