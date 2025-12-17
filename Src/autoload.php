<?php

declare(strict_types=1);


/**
 * The simplest autoloader for App\Src namespace.
 *
 * Automatically loads classes from the App\Src namespace
 * based on PSR-4 style directory structure.
 * 
 */
spl_autoload_register(function (string $class): void {

    $prefix = 'App\\Src\\';
    $baseDir = __DIR__ . '/';

    // Skip classes outside the App\Src namespace
    if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
        return;
    }

    // Convert namespace to file path
    $relativeClass = substr($class, strlen($prefix));
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (is_file($file)) {
        require $file;
    }
});
