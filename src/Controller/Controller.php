<?php

namespace Src\Controller;

use Src\Core\CustomError;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Controller
{
    protected function view(string $view, array|object $data = []): void
    {
        $loader = new FilesystemLoader(__DIR__ . "/../../public/views/");
        $twig = new Environment($loader);
        $twig->addGlobal("session", $_SESSION);
        $twig->addGlobal("BASE_URL", getenv("BASE_URL"));
        try {
            echo $twig->render($view . ".twig.php", $data);
        } catch (\Exception $e) {
            throw new CustomError("could not render this page. " . $e->getMessage(), 500);
        }
    }

    protected function json(array|object $data, int $code = 200): void
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST");
        header("Content-Type: application/json; charset=utf-8");

        http_response_code($code);

        echo json_encode($data, JSON_PRETTY_PRINT);
        exit;
    }
}