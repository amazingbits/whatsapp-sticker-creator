<?php

namespace Src\Controller;

class APIController extends Controller
{
    public function sendWhatsapp(): void
    {
        $this->json(["message" => "ok"]);
    }
}