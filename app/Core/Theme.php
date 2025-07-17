<?php

namespace App\Core;

use App\Providers\Blocks;
use App\Providers\Editor;
use Timber\Timber;

class Theme
{
    public static function init(): void
    {
        add_action('after_setup_theme', [self::class, 'setup']);
        add_action('wp_enqueue_scripts', [self::class, 'enqueueAssets']);
        add_filter('timber/context', [Context::class, 'extend']);

        self::bootTimber();
        Blocks::register();
        Editor::register();
    }

    public static function setup(): void
    {
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');
        add_theme_support('menus');
        add_theme_support('align-wide');

        register_nav_menus([
            'main'   => 'Menu principal',
            'footer' => 'Menu pied de page',
        ]);
    }

    public static function enqueueAssets(): void
    {
        $dist = get_template_directory_uri() . '/assets';
        $js   = $dist . '/js/main.js';
        $css  = $dist . '/css/tailwind.css';

        wp_enqueue_script('theme-js', $js, [], filemtime(get_template_directory() . '/assets/js/main.js'), true);
        wp_enqueue_style('theme-css', $css, [], filemtime(get_template_directory() . '/assets/css/tailwind.css'));

        wp_enqueue_style('leaflet-css', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css', [], '1.9.4');
        wp_enqueue_script('leaflet-js', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', [], '1.9.4', true);
    }

    public static function bootTimber(): void
    {
        if (!class_exists(Timber::class)) {
            add_action('admin_notices', function () {
                echo '<div class="error"><p><strong>Timber n’est pas installé.</strong> Veuillez exécuter : <code>composer require timber/timber</code></p></div>';
            });
            return;
        }

        Timber::$dirname = [
            'views',
            'views/components',
            'views/partials',
            'views/layouts',
        ];
        Timber::$autoescape = false;
    }
}
