<?php

$helpersDirectory = __DIR__ . "/helpers";

if (is_dir($helpersDirectory)) {
    $helperFiles = scandir($helpersDirectory);
    $helperFiles = array_filter($helperFiles, function ($file) {
        return pathinfo($file, PATHINFO_EXTENSION) === "php";
    });
    foreach ($helperFiles as $helperFile) {
        require_once $helpersDirectory . "/" . $helperFile;
    }
}