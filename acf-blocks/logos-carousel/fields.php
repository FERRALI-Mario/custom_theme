<?php

if (!function_exists('acf_add_local_field_group')) {
    return;
}

acf_add_local_field_group([
    'key' => 'group_logos_carousel',
    'title' => 'Bloc Logos Carousel',
    'fields' => [
        [
            'key' => 'field_logos',
            'label' => 'Logos',
            'name' => 'logos',
            'type' => 'repeater',
            'min' => 2,
            'button_label' => 'Ajouter un logo',
            'sub_fields' => [
                [
                    'key' => 'field_logo_image',
                    'label' => 'Image',
                    'name' => 'image',
                    'type' => 'image',
                    'return_format' => 'array',
                    'preview_size' => 'medium',
                    'library' => 'all',
                ],
            ],
        ],
    ],
    'location' => [
        [
            [
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/logos-carousel',
            ],
        ],
    ],
]);
