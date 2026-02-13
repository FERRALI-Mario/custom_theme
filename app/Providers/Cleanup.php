<?php

namespace App\Providers;

use App\Core\ServiceProvider;

class Cleanup extends ServiceProvider
{
    public function register(): void
    {
        // Version WP
        add_filter('the_generator', '__return_false');

        // Emojis
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('wp_print_styles', 'print_emoji_styles');

        // Versions CSS/JS
        add_filter('style_loader_src', [$this, 'removeQueryArg'], 999);
        add_filter('script_loader_src', [$this, 'removeQueryArg'], 999);
    }

    public function removeQueryArg(string $src): string
    {
        if (strpos($src, 'ver=')) {
            $src = remove_query_arg('ver', $src);
        }
        return $src;
    }
}
