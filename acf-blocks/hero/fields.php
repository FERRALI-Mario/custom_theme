<?php

if (!function_exists('acf_add_local_field_group')) return;

acf_add_local_field_group([
    'key' => 'hero',
    'title' => 'Block Hero',
    'fields' => [
        [
            'key' => 'field_hero_title',
            'label' => 'Titre',
            'name' => 'title',
            'type' => 'text',
            'required' => 1,
        ],
        [
            'key' => 'field_hero_subtitle',
            'label' => 'Sous-titre',
            'name' => 'subtitle',
            'type' => 'textarea',
            'rows' => 3,
        ],
        [
            'key' => 'field_hero_background',
            'label' => 'Image de fond',
            'name' => 'background',
            'type' => 'image',
            'return_format' => 'array',
            'preview_size' => 'medium',
        ],
        [
            'key' => 'field_hero_cta_text',
            'label' => 'Texte du bouton',
            'name' => 'cta_text',
            'type' => 'text',
            'required' => 1,
        ],
        [
            'key' => 'field_hero_cta_url',
            'label' => 'URL du bouton',
            'name' => 'cta_url',
            'type' => 'url',
            'required' => 1,
        ],
        [
            'key' => 'field_hero_image',
            'label' => 'Image',
            'name' => 'image',
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
