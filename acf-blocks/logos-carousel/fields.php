<?php

if (function_exists('acf_add_local_field_group')) :

    acf_add_local_field_group([
        'key' => 'logos_carousel',
        'title' => 'Bloc carousel de Logos',
        'fields' => [
            [
                'key' => 'field_title',
                'label' => 'Titre',
                'name' => 'title',
                'type' => 'text',
                'required' => 1,
            ],
            [
                'key' => 'field_subtitle',
                'label' => 'Sous-titre',
                'name' => 'subtitle',
                'type' => 'textarea',
                'rows' => 4,
                'required' => 0,
            ],
            [
                'key' => 'field_logos',
                'label' => 'Logos',
                'name' => 'logos',
                'type' => 'repeater',
                'button_label' => 'Ajouter un logo',
                'sub_fields' => [
                    [
                        'key' => 'field_logo_image',
                        'label' => 'Logo',
                        'name' => 'logo_image',
                        'type' => 'image',
                        'return_format' => 'array',
                        'preview_size' => 'medium',
                        'required' => 1,
                    ],
                    [
                        'key' => 'field_logo_url',
                        'label' => 'URL du logo',
                        'name' => 'logo_url',
                        'type' => 'url',
                        'required' => 0,
                    ],
                ],
                'min' => 1,
                'max' => 10,
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/logos-carousel',
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
