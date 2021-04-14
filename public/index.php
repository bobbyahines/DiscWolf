<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/vendor/autoload.php';

session_start();

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/', '\DiscWolf\Controllers\HomeController/index');
    $r->addRoute('GET', '/play', '\DiscWolf\Controllers\HomeController/play');
    $r->addRoute('GET', '/rules', '\DiscWolf\Controllers\HomeController/rules');
    $r->addRoute('GET', '/about', '\DiscWolf\Controllers\HomeController/about');
    $r->addRoute('POST', '/registerPlayers', '\DiscWolf\Controllers\SetupController/register');
    $r->addRoute('POST', '/terms', '\DiscWolf\Controllers\SetupController/terms');
    $r->addRoute('POST', '/hole/{id:\d+}', '\DiscWolf\Controllers\GameController/index');
    $r->addRoute('POST', '/score', '\DiscWolf\Controllers\FinalScoreController/index');
    $r->addRoute('GET', '/test', '\DiscWolf\Controllers\Controller/test');
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = ['GET' => $_GET, 'POST' => $_POST];
        [$class, $method] = explode("/", $handler, 2);
        call_user_func_array([new $class, $method], $vars);
        break;
}