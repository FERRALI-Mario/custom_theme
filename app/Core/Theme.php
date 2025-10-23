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
        self::registerBlockCategories();
        Blocks::register();
        Editor::register();

        add_action('after_setup_theme', static function () {
            if (\defined('WP_CLI') && \class_exists('\\WP_CLI') && \class_exists(\App\Console\InitCommand::class)) {
                \App\Console\InitCommand::register();
            }
        }, 5);
    }

    public static function setup(): void
    {
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');
        add_theme_support('menus');
        add_theme_support('align-wide');

        register_nav_menus([
            'primary'   => 'Menu principal',
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

        /** MAPS */
        wp_enqueue_style('leaflet-css', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css', [], '1.9.4');
        wp_enqueue_script('leaflet-js', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', [], '1.9.4', true);

        /** FONTAWESOME */
        wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css', [], null);
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
    }

    public static function registerBlockCategories(): void
    {
        add_filter('block_categories_all', function (array $categories, $editor_context) {
            $new_categories = [
                ['slug' => 'contenu', 'title' => '🎨 Contenu'],
                ['slug' => 'woocommerce', 'title' => '🛒 WooCommerce'],
                ['slug' => 'mise-en-avant', 'title' => '🧩 Mise en avant'],
                ['slug' => 'contact', 'title' => '📇 Contact'],
                ['slug' => 'relations', 'title' => '👥 Équipe & Témoignages'],
            ];

            return array_merge($new_categories, $categories);
        }, 10, 2);
    }
}
