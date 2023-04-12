<?php

namespace TimeDesign;

use Exception;
use TimeDesign\Controllers\AboutController;
use TimeDesign\Controllers\AdminController;
use TimeDesign\Controllers\ClubController;
use TimeDesign\Controllers\CourseController;
use TimeDesign\Controllers\HomeController;
use TimeDesign\Controllers\InputController;
use TimeDesign\Controllers\AuthenticationController;
use TimeDesign\Controllers\JobController;
use TimeDesign\Controllers\ScheduleController;
use TimeDesign\Controllers\SearchController;
use TimeDesign\Response\PageResponse;
use TimeDesign\Response\Response;

class Router {

    const ROUTE_PREFIX = '/TimeDesign';

    /** @var array[][] $routes */
    private $routes;

    private $controller;

    private $core;

    public function __construct(Core $core) {
        $this->core = $core;
        $this->routes = [];
        $this->routes['GET'] = [];
        $this->routes['POST'] = [];
        $this->controller = null;
        $this->initRoutes();
    }

    private function addRoute(
        string   $path,
        string   $method,
        array    $action,
        bool     $requires_auth = false,
        bool     $requires_admin = false
    ) {
        if ($method !== 'GET' && $method !== 'POST') {
            throw new Exception("Method not supported!");
        }
        $this->routes[$method][self::ROUTE_PREFIX . $path] = [$action, $requires_auth, $requires_admin];
    }

    private function initRoutes() {
        $this->addRoute('/', 'GET', [HomeController::class, 'index']);
        $this->addRoute('/search', 'GET', [SearchController::class, 'index']);
        $this->addRoute('/input', 'GET', [InputController::class, 'index'], true);
        $this->addRoute('/schedule', 'GET', [ScheduleController::class, 'index']);
        $this->addRoute('/about', 'GET', [AboutController::class, 'index']);
        $this->addRoute('/login', 'GET', [AuthenticationController::class, 'login']);
        $this->addRoute('/logout', 'GET', [AuthenticationController::class, 'logout']);
        $this->addRoute('/courses', 'GET', [CourseController::class, 'getCourse']);
        $this->addRoute('/jobs', 'GET', [JobController::class, 'getJob']);
        $this->addRoute('/clubs', 'GET', [ClubController::class, 'getClub']);
        $this->addRoute('/course', 'POST', [InputController::class, 'newCourse'], true);
        $this->addRoute('/job', 'POST', [InputController::class, 'newJob'], true);
        $this->addRoute('/club', 'POST', [InputController::class, 'newClub'], true);
        $this->addRoute('/input/course', 'POST', [InputController::class, 'submitCourseReview'], true);
        $this->addRoute('/input/job', 'POST', [InputController::class, 'submitJobReview'], true);
        $this->addRoute('/input/club', 'POST', [InputController::class, 'submitClubReview'], true);
        $this->addRoute('/admin', 'GET', [AdminController::class, 'index'], true, true);
        $this->addRoute('/admin/verify_course', 'POST', [AdminController::class, 'verifyCourse'], true, true);
        $this->addRoute('/admin/verify_job', 'POST', [AdminController::class, 'verifyJob'], true, true);
        $this->addRoute('/admin/verify_club', 'POST', [AdminController::class, 'verifyClub'], true, true);
        $this->addRoute('/calculate', 'GET', [HomeController::class, 'getData']);
    }

    public function matchRoute() {
        $method = $_SERVER['REQUEST_METHOD'];
        if (!array_key_exists($method, $this->routes)) {
            return;
        }
        $routes = $this->routes[$method];
        $url = parse_url($_SERVER['REQUEST_URI']);
        $path = $url['path'];
        if (!array_key_exists($path, $routes)) {
            return;
        }
        if ($routes[$path][1] === true && !$this->core->isLoggedIn()) {
            return;
        }
        if ($routes[$path][2] === true && !$this->core->getLoggedInUser()->isIsAdmin()) {
            return;
        }
        $this->controller = $routes[$path][0];
    }

    public function go(): Response {
        if ($this->controller === null) {
            return new PageResponse($this->core, '404.twig', []);
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->checkXSRF()) {
                return new PageResponse($this->core, 'Error.twig', [
                    'error' => 'Invalid XSRF token!'
                ]);
            }
        }
        $controller = new $this->controller[0]($this->core);
        $func_name = $this->controller[1];
        return $controller->$func_name();
    }

    public function checkXSRF(): bool {
        if (!isset($_POST['xsrf_token'])) {
            return false;
        }
        return $_POST['xsrf_token'] === $this->core->getXSRFToken();
    }
}
