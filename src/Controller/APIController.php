<?php

namespace Src\Controller;

use Src\Core\CustomError;
use Src\Processor\ImageProcessor;
use Src\Repository\Imp\ImageRepositoryCloudinary;
use Src\Repository\Imp\ImageRepositoryLocal;
use Src\Service\ImageService;
use Src\Service\WhatsappService;

class APIController extends Controller
{
    public function sendWhatsapp(): void
    {
        $phoneNumber = $_POST["phoneNumber"] ?? null;

        if (!$phoneNumber) {
            throw new CustomError("No phone number provided", 400);
        }

        $phoneNumber = format_phone_number($phoneNumber);
        if (!$phoneNumber) {
            throw new CustomError("phone number is invalid", 400);
        }

        if (!isset($_FILES)) {
            throw new CustomError("you need upload a file", 400);
        }

        if (!isset($_FILES["image"])) {
            throw new CustomError("'image' is missing", 400);
        }

        $image = $_FILES["image"];
        $localService = new ImageService(new ImageProcessor(), new ImageRepositoryLocal());
        $cloudService = new ImageService(new ImageProcessor(), new ImageRepositoryCloudinary());

        $upload = $cloudService->upload($image);
        if (!$upload) {
            throw new CustomError("upload failed", 400);
        }

        $whatsAppService = new WhatsappService();
        $message = $whatsAppService->sendMessage($phoneNumber, null, $upload);

        $cloudService->delete($upload);
        $localService->delete($upload);

        if ($message->errorCode) {
            $this->json([
                "status" => 500,
                "error" => "Server internal error",
                "message" => $message->errorMessage
            ], 500);
        }

        $this->json([
            "status" => 200,
            "error" => null,
            "message" => "message sent successfully"
        ]);
    }
}