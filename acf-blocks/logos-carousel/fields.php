<?php

if (function_exists('acf_add_local_field_group')) :

acf_add_local_field_group([
    'key' => 'group_logos_carousel',
    'title' => 'Logos Carousel Block',
    'fields' => [
        [
            'key' => 'field_title',
            'label' => 'Titre',
            'name' => 'title',
            'type' => 'text',
            'required' => 1, // Champ requis
        ],
        [
            'key' => 'field_text',
            'label' => 'Texte',
            'name' => 'text',
            'type' => 'textarea',
            'rows' => 4,
            'required' => 0, // Champ non requis
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
                    'label' => 'URL du logo (optionnel)',
                    'name' => 'logo_url',
                    'type' => 'url',
                    'required' => 0,
                ],
            ],
            'min' => 1, // Au moins un logo
            'max' => 10, // Maximum 10 logos
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
