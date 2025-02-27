<?php

namespace Src\Service;

use Src\Core\CustomError;
use Src\Processor\ImageProcessor;
use Src\Repository\ImageRepository;

class ImageService
{
    private ImageProcessor $imageProcessor;
    private ImageRepository $imageRepository;

    public function __construct(ImageProcessor $imageProcessor, ImageRepository $imageRepository)
    {
        $this->imageProcessor = $imageProcessor;
        $this->imageRepository = $imageRepository;
    }

    public function upload(array $image): ?string
    {
        try {
            $processedImage = $this->imageProcessor->convertToWebP($image);
            $expProcessedImage = explode("/", $processedImage);
            $imageName = $expProcessedImage[count($expProcessedImage) - 1];

            return $this->imageRepository->store($imageName, $processedImage);
        } catch (\Exception  $e) {
            throw new CustomError("image upload service failed. " . $e->getMessage(), 500);
        }
    }

    public function delete(string $filePath): void
    {
        $this->imageRepository->delete($filePath);
    }
}