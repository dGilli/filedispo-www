<?php

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

return function () {
    $loader = new FilesystemLoader(__DIR__ . '/templates');
    $twig = new Environment($loader);

    echo $twig->render('index.twig', ['name' => 'World']);
};
