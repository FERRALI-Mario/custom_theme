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
        if (function_exists('acf_add_options_page')) {
            acf_add_options_page([
                'page_title'    => 'Réglages du Thème',
                'menu_title'    => 'Thème',
                'menu_slug'     => 'acf-options-generale',
                'capability'    => 'edit_posts',
                'redirect'      => false,
                'position'      => 22,
                'icon_url'      => 'dashicons-layout',
            ]);
        }

        if (function_exists('acf_add_options_sub_page')) {
            acf_add_options_sub_page([
                'page_title'  => 'Réglages Stripe',
                'menu_title'  => 'Stripe',
                'parent_slug' => 'acf-options-generale',
                'menu_slug'   => 'acf-options-stripe',
            ]);
        }

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
                'location' => [[['param' => 'options_page', 'operator' => '==', 'value' => 'acf-options-stripe']]],
            ]);

            // --- CHAMPS FOOTER ---
            acf_add_local_field_group([
                'key' => 'group_footer_settings',
                'title' => 'Réglages du Pied de page (Footer)',
                'fields' => [
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
                ],
                'location' => [
                    [
                        ['param' => 'options_page', 'operator' => '==', 'value' => 'acf-options-generale'],
                    ],
                ],
            ]);
        }
    }
}
