<?php

namespace App\Support;

class Helpers
{
    /**
     * Dump variable in console (browser)
     */
    public static function consoleLog($data): void
    {
        if (is_array($data) || is_object($data)) {
            $data = json_encode($data);
        }
        echo "<script>console.log('WP_LOG: ' + " . "{$data}" . ");</script>";
    }

    /**
     * Get SVG icon by slug
     */
    public static function getSvgIcon(string $slug): string
    {
        $path = get_stylesheet_directory() . "/assets/images/icons/{$slug}.svg";
        if (file_exists($path)) {
            return file_get_contents($path);
        }
        return '';
    }
}