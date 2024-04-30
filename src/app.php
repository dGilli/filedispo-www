<?php

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

function processFiles(string ...$files): array {
    $processedFiles = [];

    foreach ($files as $file) {
        if (!is_string($file)) {
            throw new InvalidArgumentException("Each file must be a string representing a file path.");
        }
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

function formatFileSize(int $size): string {
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

$data = [
    "downloads" => processFiles(
        "test.pdf",
        "test.md",
    ),
];

return function () use ($data): void {
    $requestPath = $_GET['p'] ?? '/';

    echo $requestPath;

    $loader = new FilesystemLoader(__DIR__ . '/templates');
    $twig = new Environment($loader);

    echo $twig->render('index.twig', ['data' => $data]);
};
