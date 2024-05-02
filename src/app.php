<?php

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

function processFiles(array $filenames): array {
    $processedFiles = [];

    foreach ($filenames as $filename) {
        $file = $filename;

        $filetype = pathinfo($file, PATHINFO_EXTENSION);
        $filesize = filesize(__DIR__ . '/../shared/' .$file); // in bytes

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

return function (): void {
    $debug = getenv('DEBUG') === 'true';
    $correctPassword = "Hire Me!";

    $requestPath = $_GET['p'] ?? '/';

    $loader = new FilesystemLoader(__DIR__ . '/templates');
    $twig = new Environment($loader, [
        'debug' => $debug,
        'cache' => '/var/cache/app/twig/compilation_cache',
    ]);

    session_start();

    if (empty($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
        $errors = [];

        if ($requestPath === "verify" && $_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($_POST['password'] == $correctPassword) {
                $_SESSION['authenticated'] = true;
            } else {
                $errors[] = 'Incorrect password. Please try again.';
            }
            header('Location: /');
        }

        $twig->display('login.twig', ['data' => [
            "redirectPath" => $requestPath,
            "errors" => $errors,
        ],
            'errors' => $errors,
        ]);
        exit;
    }

    $filenames = array_diff(scandir("/var/app/shared"), array('.', '..'));
    $filename = preg_replace('/download\//', '', $requestPath);

    if (array_search($filename, $filenames)) {
        $file = '/var/app/shared/' . $filename;

        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($file) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            flush(); // Flush system output buffer
            readfile($file);
        }
    }

    $twig->display('index.twig', ['data' => [
        "downloads" => processFiles($filenames),
    ]]);
};
