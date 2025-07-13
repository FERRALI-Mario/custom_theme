<?php

if (!function_exists('acf_add_local_field_group')) {
    return;
}

acf_add_local_field_group([
    'key'    => 'group_hero_fields',
    'title'  => 'Bloc Hero',
    'fields' => [
        [
            'key'     => 'field_hero_title',
            'label'   => 'Titre',
            'name'    => 'hero_title',
            'type'    => 'text',
            'required'=> 1,
            'wrapper' => [
                'width' => '100',
            ],
        ],
        [
            'key'     => 'field_hero_subtitle',
            'label'   => 'Sous-titre',
            'name'    => 'hero_subtitle',
            'type'    => 'textarea',
            'required'=> 0,
            'rows'    => 3,
            'wrapper' => [
                'width' => '100',
            ],
        ],
        [
            'key'           => 'field_hero_background',
            'label'         => 'Image de fond',
            'name'          => 'hero_background',
            'type'          => 'image',
            'return_format' => 'array',
            'preview_size'  => 'medium',
            'library'       => 'all',
            'wrapper'       => [
                'width' => '100',
            ],
        ],
    ],
    'location' => [
        [
            [
                'param'    => 'block',
                'operator' => '==',
                'value'    => 'acf/hero',
            ],
        ],
    ],
    'style'                 => 'default',
    'position'              => 'acf_after_title',
    'label_placement'       => 'top',
    'instruction_placement' => 'label',
    'active'                => true,
    'show_in_rest'          => false,
]);
