<?php

namespace Src\Processor;

use Src\Core\CustomError;

class ImageProcessor
{
    private string $uploadLocalPath;

    public function __construct()
    {
        $this->uploadLocalPath = __DIR__ . "/../../public/" . getenv("UPLOAD_FOLDER");
        if (!is_dir($this->uploadLocalPath)) {
            mkdir($this->uploadLocalPath, 0777, true);
        }
    }

    public function convertToWebP(array $image): ?string
    {
        $allowedExtensions = ["jpg", "jpeg", "png", "gif"];
        $extension = mb_strtolower(pathinfo($image["name"], PATHINFO_EXTENSION));

        if (!in_array($extension, $allowedExtensions)) {
            throw new CustomError("invalid image extension");
        }

        $stickerName = uniqid("sticker_") . ".webp";
        $stickerPath = $this->uploadLocalPath . "/" . $stickerName;

        $img = match ($extension) {
            "jpg", "jpeg" => imagecreatefromjpeg($image["tmp_name"]),
            "png" => imagecreatefrompng($image["tmp_name"]),
            "gif" => imagecreatefromgif($image["tmp_name"]),
            default => null
        };

        if (!$img) {
            throw new CustomError("image processing failed");
        }

        $origWidth = imagesx($img);
        $origHeight = imagesy($img);
        $size = max($origWidth, $origHeight);

        $resizedImg = imagecreatetruecolor(512, 512);
        imagealphablending($resizedImg, false);
        imagesavealpha($resizedImg, true);
        $transparent = imagecolorallocatealpha($resizedImg, 0, 0, 0, 127);
        imagefill($resizedImg, 0, 0, $transparent);

        imagecopyresampled(
            $resizedImg, $img,
            0, 0, 0, 0,
            512, 512,
            $origWidth, $origHeight
        );

        imagewebp($img, $stickerPath, 80);

        imagedestroy($img);
        imagedestroy($resizedImg);

        sleep(5);

        return file_exists($stickerPath) ? $stickerPath : null;
    }
}