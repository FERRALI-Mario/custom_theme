<?php

namespace App\Core;

use App\Providers\Blocks;
use App\Providers\Editor;
use App\Providers\PostTypes;
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
        PostTypes::register();
        Blocks::register();
        Editor::register();

        add_action('template_redirect', [\App\Core\Router::class, 'run']);

        $calendarHandlerPath = get_template_directory() . '/acf-blocks/calendar/AjaxHandler.php';

        if (file_exists($calendarHandlerPath)) {
            require_once $calendarHandlerPath;
            if (class_exists('AcfBlocks\Calendar\AjaxHandler')) {
                \AcfBlocks\Calendar\AjaxHandler::register();
            }
        }

        $paymentPath = get_template_directory() . '/app/Paiement/PaymentController.php';
        if (file_exists($paymentPath)) {
            require_once $paymentPath;
        }

        add_action('acf/init', [self::class, 'registerGlobalConfig']);

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

        wp_enqueue_script('theme-js', $dist . '/js/main.js', [], filemtime(get_template_directory() . '/assets/js/main.js'), true);
        wp_enqueue_style('theme-css', $dist . '/css/tailwind.css', [], filemtime(get_template_directory() . '/assets/css/tailwind.css'));

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
                ['slug' => 'maison', 'title' => '🏡 Maison'],
                ['slug' => 'woocommerce', 'title' => '🛒 WooCommerce'],
                ['slug' => 'mise-en-avant', 'title' => '🧩 Mise en avant'],
                ['slug' => 'contact', 'title' => '📇 Contact'],
                ['slug' => 'relations', 'title' => '👥 Équipe & Témoignages'],
            ];

            return array_merge($new_categories, $categories);
        }, 10, 2);
    }

    public static function registerGlobalConfig(): void
    {
        if (function_exists('acf_add_options_sub_page')) {
            acf_add_options_sub_page([
                'page_title'  => 'Réglages Stripe',
                'menu_title'  => 'Stripe',
                'parent_slug' => 'options-general.php',
                'menu_slug'   => 'acf-options-stripe',
                'capability'  => 'manage_options',
            ]);
        }

        if (function_exists('acf_add_local_field_group')) {
            acf_add_local_field_group([
                'key' => 'group_stripe_configuration',
                'title' => 'Configuration Stripe',
                'fields' => [
                    [
                        'key' => 'field_stripe_mode',
                        'label' => 'Mode',
                        'name' => 'stripe_mode',
                        'type' => 'radio',
                        'choices' => ['test' => 'Mode Test', 'live' => 'Mode Live'],
                        'default_value' => 'test',
                        'layout' => 'horizontal',
                    ],
                    [
                        'key' => 'field_stripe_test_key',
                        'label' => 'Clé Secrète (Test)',
                        'name' => 'stripe_test_key',
                        'type' => 'text',
                        'conditional_logic' => [[['field' => 'field_stripe_mode', 'operator' => '==', 'value' => 'test']]],
                    ],
                    [
                        'key' => 'field_stripe_live_key',
                        'label' => 'Clé Secrète (Live)',
                        'name' => 'stripe_live_key',
                        'type' => 'text',
                        'conditional_logic' => [[['field' => 'field_stripe_mode', 'operator' => '==', 'value' => 'live']]],
                    ],
                ],
                'location' => [[['param' => 'options_page', 'operator' => '==', 'value' => 'acf-options-stripe']]],
            ]);
        }
    }
}
