<?php

namespace TimeDesign\Controllers;

use TimeDesign\Response\PageResponse;
use TimeDesign\Response\RedirectResponse;

class AdminController extends Controller {
    public function index(): PageResponse {
        return new PageResponse($this->core, "Admin.twig", [
            'courses' => $this->core->getDbQueries()->getUnverifiedCourses(),
            'jobs' => $this->core->getDbQueries()->getUnverifiedJobs(),
            'clubs' => $this->core->getDbQueries()->getUnverifiedClubs()
        ]);
    }

    public function verifyCourse(): RedirectResponse {
        if (empty($_POST['course_id'])) {
            return new RedirectResponse($this->core, "/TimeDesign/admin");
        }
        $this->core->getDbQueries()->verifyCourse($_POST['course_id']);
        return new RedirectResponse($this->core, "/TimeDesign/admin");
    }

    public function verifyJob(): RedirectResponse {
        if (empty($_POST['job_id'])) {
            return new RedirectResponse($this->core, "/TimeDesign/admin");
        }
        $this->core->getDbQueries()->verifyJob($_POST['job_id']);
        return new RedirectResponse($this->core, "/TimeDesign/admin");
    }

    public function verifyClub(): RedirectResponse {
        if (empty($_POST['club_id'])) {
            return new RedirectResponse($this->core, "/TimeDesign/admin");
        }
        $this->core->getDbQueries()->verifyClub($_POST['club_id']);
        return new RedirectResponse($this->core, "/TimeDesign/admin");
    }
}
