<?php

namespace Src\Repository;

interface ImageRepository
{
    public function store(string $fileName, string $filePath): ?string;

    public function delete(string $filePath): void;
}