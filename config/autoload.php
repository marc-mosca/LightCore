<?php

spl_autoload_register(
    function (string $class): void
    {
        if (str_starts_with($class, 'App\\') === true)
        {
            $class = str_replace("App\\", "/src/", $class);
        }

        $file = BASE_PATH . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';

        if (file_exists($file) === true)
        {
            require_once $file;
        }
    }
);
