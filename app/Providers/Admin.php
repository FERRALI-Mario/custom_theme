<?php

namespace App\Providers;

use App\Core\ServiceProvider;

class Admin extends ServiceProvider
{
    public function register(): void
    {
        add_action('acf/init', [$this, 'registerGlobalConfig']);
    }

    public function registerGlobalConfig(): void
    {
        // Unified options page
        if (function_exists('acf_add_options_page')) {
            acf_add_options_page([
                'page_title'    => 'Réglages du thème',
                'menu_title'    => 'Réglages thème',
                'menu_slug'     => 'acf-theme-settings',
                'capability'    => 'edit_posts',
                'redirect'      => false,
                'position'      => 22,
                'icon_url'      => 'dashicons-layout',
            ]);
        }

        if (function_exists('acf_add_local_field_group')) {
            // Single unified field group with tabs
            acf_add_local_field_group([
                'key' => 'group_theme_settings',
                'title' => 'Réglages du thème',
                'fields' => [
                    // --- TAB CHAMPS GLOBAUX ---
                    [
                        'key' => 'field_tab_global',
                        'label' => 'Champs globaux',
                        'type' => 'tab',
                        'placement' => 'top',
                        'endpoint' => 0,
                    ],
                    [
                        'key' => 'field_global_phone',
                        'label' => 'Téléphone',
                        'name' => 'global_phone',
                        'type' => 'text',
                        'placeholder' => '+33 X XX XX XX XX',
                    ],
                    // --- TAB MENUS ---
                    [
                        'key' => 'field_tab_menus',
                        'label' => 'Menus',
                        'type' => 'tab',
                        'placement' => 'top',
                        'endpoint' => 0,
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
                    [
                        'key' => 'field_footer_socials',
                        'label' => 'Réseaux sociaux',
                        'name' => 'social_networks',
                        'type' => 'repeater',
                        'layout' => 'table',
                        'button_label' => 'Ajouter un réseau',
                        'sub_fields' => [
                            [
                                'key' => 'field_social_icon',
                                'label' => 'Réseau',
                                'name' => 'icon',
                                'type' => 'select',
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
                    // --- TAB STRIPE ---
                    [
                        'key' => 'field_tab_stripe',
                        'label' => 'Stripe',
                        'type' => 'tab',
                        'placement' => 'top',
                        'endpoint' => 0,
                    ],
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
                'location' => [[['param' => 'options_page', 'operator' => '==', 'value' => 'acf-theme-settings']]],
            ]);
        }
    }
}
