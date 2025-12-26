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

        $contactHandlerPath = get_template_directory() . '/acf-blocks/contact-form/AjaxHandler.php';

        if (file_exists($contactHandlerPath)) {
            require_once $contactHandlerPath;
            if (class_exists('AcfBlocks\ContactForm\AjaxHandler')) {
                \AcfBlocks\ContactForm\AjaxHandler::register();
            }
        }

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
        // 1. CRÉATION DE LA PAGE D'OPTIONS PRINCIPALE
        if (function_exists('acf_add_options_page')) {
            acf_add_options_page([
                'page_title'    => 'Réglages du Thème',
                'menu_title'    => 'Thème', // Le nom dans le menu
                'menu_slug'     => 'acf-options-generale', // L'ID pour relier les champs
                'capability'    => 'edit_posts',
                'redirect'      => false, // false = on peut mettre des champs sur cette page
                'position'      => 22, // 20 = Pages, 25 = Commentaires. Donc 22 = Juste en dessous de Pages.
                'icon_url'      => 'dashicons-layout', // Icône
            ]);
        }

        // 2. SOUS-PAGE STRIPE (Optionnel, si vous voulez le garder séparé)
        if (function_exists('acf_add_options_sub_page')) {
            acf_add_options_sub_page([
                'page_title'  => 'Réglages Stripe',
                'menu_title'  => 'Stripe',
                'parent_slug' => 'acf-options-generale', // On le met en enfant de "Thème" pour ranger
                'menu_slug'   => 'acf-options-stripe',
            ]);
        }

        // 3. DÉFINITION DES CHAMPS
        if (function_exists('acf_add_local_field_group')) {

            // --- CHAMPS STRIPE ---
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
                // On attache Stripe à la sous-page Stripe
                'location' => [[['param' => 'options_page', 'operator' => '==', 'value' => 'acf-options-stripe']]],
            ]);

            // --- CHAMPS FOOTER ---
            acf_add_local_field_group([
                'key' => 'group_footer_settings',
                'title' => 'Réglages du Pied de page (Footer)',
                'fields' => [
                    // Onglet Colonnes
                    [
                        'key' => 'field_tab_footer_columns',
                        'label' => 'Colonnes',
                        'type' => 'tab',
                    ],
                    [
                        'key' => 'field_footer_title_menu_1',
                        'label' => 'Titre colonne 1 (Menu principal)',
                        'name' => 'footer_title_menu',
                        'type' => 'text',
                        'default_value' => 'Menu',
                    ],
                    [
                        'key' => 'field_footer_title_menu_2',
                        'label' => 'Titre colonne 2 (Infos)',
                        'name' => 'footer_title_infos',
                        'type' => 'text',
                        'default_value' => 'Informations',
                    ],

                    // Onglet Réseaux Sociaux
                    [
                        'key' => 'field_tab_footer_social',
                        'label' => 'Réseaux Sociaux',
                        'type' => 'tab',
                    ],
                    [
                        'key' => 'field_footer_socials',
                        'label' => 'Liste des réseaux',
                        'name' => 'social_networks',
                        'type' => 'repeater',
                        'layout' => 'table',
                        'button_label' => 'Ajouter un réseau',
                        'sub_fields' => [
                            [
                                'key' => 'field_social_icon',
                                'label' => 'Réseau',
                                'name' => 'icon', // On garde le nom 'icon'
                                'type' => 'select', // On passe en SELECT comme votre bloc
                                'choices' => [
                                    'facebook'  => 'Facebook',
                                    'instagram' => 'Instagram',
                                    'snapchat'  => 'Snapchat',
                                    'tiktok'    => 'TikTok',
                                    'pinterest' => 'Pinterest',
                                    'github'    => 'GitHub',
                                    'linkedin'  => 'LinkedIn',
                                    'twitter'   => 'X (Twitter)',
                                    'youtube'   => 'YouTube',
                                ],
                                'default_value' => 'instagram',
                            ],
                            [
                                'key' => 'field_social_url',
                                'label' => 'Lien URL',
                                'name' => 'url',
                                'type' => 'url',
                            ],
                        ],
                    ],
                ],
                // On attache le Footer à la page principale "Thème"
                'location' => [
                    [
                        ['param' => 'options_page', 'operator' => '==', 'value' => 'acf-options-generale'],
                    ],
                ],
            ]);
        }
    }
}
