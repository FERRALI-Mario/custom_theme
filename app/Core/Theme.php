<?php

// app/Core/Theme.php
namespace App\Core;

use Timber\Timber;

class Theme
{
    public static function init()
    {
        // Autoload via composer
        // Load textdomain, theme supports, hooks
        add_action('after_setup_theme', [self::class, 'setupTheme']);
        add_action('init', [self::class, 'registerFeatures']);
    }

    public static function setupTheme()
    {
        load_theme_textdomain('wp-theme-boilerplate', get_template_directory() . '/languages');
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');
        add_theme_support('html5', ['search-form', 'gallery', 'caption']);
        add_theme_support('custom-logo');

        // Register menus
        register_nav_menus([
            'main' => __('Main Menu', 'wp-theme-boilerplate'),
            'footer' => __('Footer Menu', 'wp-theme-boilerplate'),
        ]);
    }

    public static function registerFeatures()
    {
        // Enqueue assets
        add_action('wp_enqueue_scripts', [self::class, 'enqueueAssets'], 20);

        // Initialize Timber
        Timber::init();
    }

    public static function enqueueAssets()
    {
        $theme = wp_get_theme();
        $version = $theme->get('Version');

        // Styles
        wp_enqueue_style(
            'tailwind',
            get_stylesheet_directory_uri() . '/assets/css/tailwind.css',
            [],
            $version
        );

        // Main JS built by Vite
        wp_enqueue_script(
            'theme-main',
            get_stylesheet_directory_uri() . '/assets/js/main.js',
            [],
            $version,
            true
        );
    }
}

// Bootstrap theme
Theme::init();