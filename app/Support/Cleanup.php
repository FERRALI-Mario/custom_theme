<?php 

namespace App\Support;

class Cleanup
{
    public static function init()
    {
        // Remove WP version from head
        add_filter('the_generator', '__return_false');

        // Remove emoji scripts
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('wp_print_styles', 'print_emoji_styles');

        // Other cleanup
        add_filter('style_loader_src', [self::class, 'removeQueryArg'], 999);
        add_filter('script_loader_src', [self::class, 'removeQueryArg'], 999);
    }

    public static function removeQueryArg($src)
    {
        return remove_query_arg('ver', $src);
    }
}

// Initialize cleanup
Cleanup::init();