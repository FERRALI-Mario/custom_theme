<?php

namespace App\Providers;

use App\Core\BlockFactory;

class Blocks
{
    protected const NAMESPACE = 'AcfBlocks';

    public static function register(): void
    {
        $jsonPath = get_template_directory() . '/active-blocks.json';
        $blocksToLoad = [];

        if (file_exists($jsonPath)) {
            $blocksToLoad = json_decode(file_get_contents($jsonPath), true);
        } else {
            $config = include get_template_directory() . '/config/blocks.php';
            $blocksToLoad = $config['core'];
        }

        $blocksDir = get_template_directory() . '/acf-blocks/';

        foreach ($blocksToLoad as $slug) {
            $path = $blocksDir . $slug . '/fields.php';
            $classPath = get_template_directory() . "/acf-blocks/{$slug}/Controller.php";

            if (!is_dir($blocksDir . $slug)) {
                continue;
            }

            $namespace = "AcfBlocks\\" . self::dashesToCamelCase($slug) . "\\Controller";

            if (file_exists($classPath)) {
                require_once $classPath;
                if (class_exists($namespace)) {
                    new $namespace();
                }
            } elseif (file_exists($path)) {
                require_once $path;
            }
        }
    }

    private static function dashesToCamelCase($string, $capitalizeFirstCharacter = true)
    {
        $str = str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
        if (!$capitalizeFirstCharacter) {
            $str[0] = strtolower($str[0]);
        }
        return $str;
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
