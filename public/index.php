<?php

use TimeDesign\Core;
use TimeDesign\Router;

session_start();

require_once __DIR__ . "/../vendor/autoload.php";

$core = new Core();

$router = new Router($core);

$router->matchRoute();

$response = $router->go();

echo $response->render();
