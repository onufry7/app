<?php

declare(strict_types=1);


namespace App\Src;

use \RuntimeException;
use \Throwable;

class View 
{
    public function render(string $template, array $data = []): Void
    {
        try {
            $file = $this->getViewFile($template);
        } catch (Throwable $th) {
            ob_start();
            $main = $th;
            include __DIR__ . '/../views/layout.php';
            exit;
        }

        extract($data);
        ob_start();
        include $file;

        $main = ob_get_clean();
        include __DIR__ . '/../views/layout.php';
    }

    private function getViewFile(string $viewName): string
    {
        $file = __DIR__ . '/../views/' . $viewName . '.php';

        if (!is_file($file)) {
            throw new RuntimeException("No view: $viewName");
        }

        return $file;
    }
}
