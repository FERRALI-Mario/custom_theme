<?php

namespace App\Providers;

use App\Core\BlockFactory;

class Blocks
{
    protected const NAMESPACE = 'AcfBlocks';

    public static function register(): void
    {
        $basePath = get_template_directory() . '/acf-blocks/';
        $folders = glob($basePath . '*', GLOB_ONLYDIR);

        foreach ($folders as $folder) {
            self::loadBlock(basename($folder));
        }
    }

    protected static function loadBlock(string $slug): void
    {
        $className = self::resolveClassName($slug);
        $controllerPath = get_template_directory() . "/acf-blocks/{$slug}/Controller.php";

        if (file_exists($controllerPath)) {
            require_once $controllerPath;
        }

        if (class_exists($className)) {
            $instance = new $className();
        }
    }

    protected static function resolveClassName(string $slug): string
    {
        $formatted = str_replace(' ', '', ucwords(str_replace('-', ' ', $slug)));
        return self::NAMESPACE . "\\{$formatted}\\Controller";
    }
}
