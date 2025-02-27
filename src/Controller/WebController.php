<?php

namespace Src\Controller;

use Src\Core\CustomError;

class WebController extends Controller
{
    public function home(): void
    {
        $this->view("home", ["title" => "Home"]);
    }

    public function error($data): void
    {
        $statusCode = (int)$data["statusCode"];
        $this->view("error", ["title" => "Erro {$statusCode}", "statusCode" => $statusCode]);
    }
}