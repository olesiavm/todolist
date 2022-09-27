<?php

spl_autoload_register(function ($name) {
    $fileName = basename(str_replace('\\', '/', $name));
    $controllerFile = __DIR__ . "/controller/" . $fileName . ".php";
    $modelFile = __DIR__ . "/model/" . $fileName . ".php";

    if (file_exists($controllerFile)) {
        require_once($controllerFile);
    }
    if (file_exists($modelFile)) {
        require_once($modelFile);
    }
});