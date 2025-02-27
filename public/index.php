<?php

// global configuration
date_default_timezone_set("America/Sao_Paulo");
session_start();

// load composer autoload
require_once __DIR__ . "/../vendor/autoload.php";

// load helpers
require_once __DIR__ . "/../load_helpers.php";

// register global error handler
\Src\Core\ErrorHandler::register();

$dotenv = \Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . "/../");
$dotenv->load();

$router = new \CoffeeCode\Router\Router(getenv("BASE_URL"));
$router->namespace("Src\\Controller");

$router->group(null);
$router->get("/", "WebController:home");

$router->group("ops");
$router->get("/{statusCode}", "WebController:error");

$router->group("api");
$router->post("/whatsapp/send", "APIController:sendWhatsapp");

$router->dispatch();

if ($router->error()) {
    $router->redirect("/ops/{$router->error()}");
}