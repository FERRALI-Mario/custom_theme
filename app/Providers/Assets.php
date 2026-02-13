<?php

namespace App\Providers;

use App\Core\ServiceProvider;

class Assets extends ServiceProvider
{
    public function register(): void
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue']);
    }

    public function enqueue(): void
    {
        $dist = get_template_directory_uri() . '/assets';
        $root = get_template_directory();

        wp_enqueue_script('theme-js', $dist . '/js/main.js', [], filemtime($root . '/assets/js/main.js'), true);
        wp_enqueue_style('theme-css', $dist . '/css/tailwind.css', [], filemtime($root . '/assets/css/tailwind.css'));

        // FontAwesome
        wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css', [], null);
    }
}
