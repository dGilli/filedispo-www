<?php

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$data = [
    "downloads" => processFiles(
        "test.pdf",
        "test.md",
    ),
];

function processFiles(...$files) {
    $processedFiles = [];

    foreach ($files as $file) {
        $filetype = pathinfo($file, PATHINFO_EXTENSION);
        $filename = pathinfo($file, PATHINFO_FILENAME) . "." . $filetype;
        $filesize = filesize($file); // in bytes

        $filesize = formatFileSize($filesize);

        $processedFiles[] = [
            "filetype" => $filetype,
            "filename" => $filename,
            "filesize" => $filesize,
        ];
    }

    return $processedFiles;
}

function formatFileSize($size) {
    if ($size >= 1024 * 1024 * 1024 * 1024) {
        return round($size / (1024 * 1024 * 1024 * 1024), 2) . ' TB';
    } elseif ($size >= 1024 * 1024 * 1024) {
        return round($size / (1024 * 1024 * 1024), 2) . ' GB';
    } elseif ($size >= 1024 * 1024) {
        return round($size / (1024 * 1024), 2) . ' MB';
    } elseif ($size >= 1024) {
        return round($size / 1024, 2) . ' KB';
    } else {
        return $size . ' bytes';
    }
}

return function () use ($data) {
    $requestPath = $_GET['p'] ?? '/';

    echo $requestPath;

    $loader = new FilesystemLoader(__DIR__ . '/templates');
    $twig = new Environment($loader);

    echo $twig->render('index.twig', ['data' => $data]);
};
