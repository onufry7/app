<?php

declare(strict_types=1);


namespace App\Src;


use RuntimeException;
use Throwable;


/**
 * Handles rendering of views and layouts.
 *
 * Provides methods to render templates with optional data
 * and includes them in a layout.
 * 
 */
class View 
{
    /**
     * Renders a template with optional data within the main layout.
     *
     * @param string $template
     * @param array $data
     * 
     * @return Void
     * 
     */
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


    /**
     * Returns the full path to a view file.
     *
     * @param string $viewName
     * 
     * @return string
     * 
     */
    private function getViewFile(string $viewName): string
    {
        $file = __DIR__ . '/../views/' . $viewName . '.php';

        if (!is_file($file)) {
            throw new RuntimeException("No view: $viewName");
        }

        return $file;
    }
}
