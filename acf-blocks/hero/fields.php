<?php

if (!function_exists('acf_add_local_field_group')) return;

acf_add_local_field_group([
    'key' => 'group_hero_fields',
    'title' => 'Bloc Hero',
    'fields' => [
        [
            'key' => 'field_hero_title',
            'label' => 'Titre',
            'name' => 'hero_title',
            'type' => 'text',
            'required' => 1,
        ],
        [
            'key' => 'field_hero_subtitle',
            'label' => 'Sous-titre',
            'name' => 'hero_subtitle',
            'type' => 'textarea',
            'rows' => 3,
        ],
        [
            'key' => 'field_hero_background',
            'label' => 'Image de fond',
            'name' => 'hero_background',
            'type' => 'image',
            'return_format' => 'array',
            'preview_size' => 'medium',
        ],
        [
            'key' => 'field_hero_cta_text',
            'label' => 'Texte du bouton',
            'name' => 'hero_cta_text',
            'type' => 'text',
            'required' => 1,
        ],
        [
            'key' => 'field_hero_cta_url',
            'label' => 'URL du bouton',
            'name' => 'hero_cta_url',
            'type' => 'url',
            'required' => 1,
        ],
        [
            'key' => 'field_hero_second_image',
            'label' => 'Seconde image',
            'name' => 'hero_second_image',
            'type' => 'image',
            'return_format' => 'array',
            'preview_size' => 'medium',
        ],
    ],
    'location' => [
        [
            [
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/hero',
            ]
        ]
    ],
    'style' => 'default',
    'position' => 'acf_after_title',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'active' => true,
]);
