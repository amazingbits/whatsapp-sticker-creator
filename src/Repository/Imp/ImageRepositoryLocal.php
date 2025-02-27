<?php

namespace Src\Repository\Imp;

use Src\Repository\ImageRepository;

class ImageRepositoryLocal implements ImageRepository
{

    private string $uploadDirectory;

    public function __construct()
    {
        $this->uploadDirectory = __DIR__ . "/../../../public/" . getenv("UPLOAD_FOLDER");
    }


    public function store(string $fileName, string $filePath): ?string
    {
        if (!is_dir($this->uploadDirectory)) {
            mkdir($this->uploadDirectory, 0777, true);
        }

        $path = $this->uploadDirectory . "/" . $fileName;

        if (file_exists($path)) {
            $this->delete($path);
        }

        if (!move_uploaded_file($filePath, $path)) {
            return null;
        }

        return $path;
    }

    public function delete(string $filePath): void
    {
        if (is_dir($this->uploadDirectory)) {
            $files = array_diff(scandir($this->uploadDirectory), array(".", ".."));

            foreach ($files as $file) {
                $filePath = $this->uploadDirectory . "/" . $file;
                if (is_file($filePath)) {
                    unlink($filePath);
                }
            }
        }
    }
}