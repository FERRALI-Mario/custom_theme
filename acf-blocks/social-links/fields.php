<?php

if (function_exists('acf_add_local_field_group')) :

    acf_add_local_field_group([
        'key' => 'social_links',
        'title' => 'Bloc des liens sociaux',
        'fields' => [
            [
                'key' => 'field_social_links',
                'label' => 'Liens sociaux',
                'name' => 'social_links',
                'type' => 'repeater',
                'button_label' => 'Ajouter un lien social',
                'sub_fields' => [
                    [
                        'key' => 'field_social_icon',
                        'label' => 'Icône',
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
                        ],
                        'default_value' => 'facebook',
                        'instructions' => 'Sélectionnez l\'icône du média social à afficher.',
                        'required' => 1,
                    ],
                    [
                        'key' => 'field_social_url',
                        'label' => 'URL du réseau social',
                        'name' => 'url',
                        'type' => 'url',
                        'required' => 1,
                    ],
                ],
                'min' => 1,
                'max' => 7,
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/social-links',
                ]
            ]
        ],
        'style' => 'default',
        'position' => 'acf_after_title',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'active' => true,
    ]);

endif;
