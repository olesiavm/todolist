<?php

spl_autoload_register(function ($name) {
    $fileName = basename(str_replace('\\', '/', $name));
    $file = __DIR__ . "/classes/" . $fileName . ".php";

    if (file_exists($file)) {
        require_once($file);
    }
});

require_once __DIR__ . '/classes/db/Connection.php';
