<?php

namespace Src\Repository\Imp;

use Cloudinary\Api\Admin\AdminApi;
use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Configuration\Configuration;
use Src\Core\CustomError;
use Src\Repository\ImageRepository;

class ImageRepositoryCloudinary implements ImageRepository
{

    private UploadApi $uploadApi;
    private AdminApi $adminApi;

    public function __construct()
    {
        Configuration::instance([
            "cloud" => [
                "cloud_name" => getenv("CLOUDINARY_CLOUD_NAME"),
                "api_key" => getenv("CLOUDINARY_API_KEY"),
                "api_secret" => getenv("CLOUDINARY_API_SECRET"),
            ],
            "url" => [
                "secure" => true,
            ]
        ]);
        $this->uploadApi = new UploadApi();
        $this->adminApi = new AdminApi();
    }

    public function store(string $fileName, string $filePath): ?string
    {
        try {
            $upload = $this->uploadApi->upload($filePath, [
                "folder" => "uploads"
            ]);
            return $upload["secure_url"];
        } catch (\Exception $e) {
            throw new CustomError("Error Cloudinary upload: " . $e->getMessage());
        }
    }

    public function delete(string $filePath): void
    {
        try {
            preg_match("/\/uploads\/([^\/]+)\.webp$/", $filePath, $matches);
            if (!isset($matches[1])) {
                throw new CustomError("public_id extraction error");
            }

            $publicId = "uploads/" . $matches[1];

            $this->adminApi->deleteAssets([$publicId]);
        } catch (\Exception $e) {
            throw new CustomError("Cloudinary delete file error: " . $e->getMessage());
        }
    }
}