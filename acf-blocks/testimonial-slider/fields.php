<?php

if (!function_exists('acf_add_local_field_group')) {
    return;
}

acf_add_local_field_group([
    'key' => 'group_testimonial_slider',
    'title' => 'Bloc Témoignages (Slider)',
    'fields' => [
        [
            'key' => 'field_testimonials',
            'label' => 'Témoignages',
            'name' => 'testimonials',
            'type' => 'repeater',
            'layout' => 'row',
            'button_label' => 'Ajouter un témoignage',
            'min' => 1,
            'sub_fields' => [
                [
                    'key' => 'field_avatar',
                    'label' => 'Avatar',
                    'name' => 'avatar',
                    'type' => 'image',
                    'return_format' => 'array',
                    'preview_size' => 'thumbnail',
                    'library' => 'all',
                    'wrapper' => ['width' => '33'],
                ],
                [
                    'key' => 'field_name',
                    'label' => 'Nom',
                    'name' => 'name',
                    'type' => 'text',
                    'wrapper' => ['width' => '33'],
                ],
                [
                    'key' => 'field_rating',
                    'label' => 'Note (1–5)',
                    'name' => 'rating',
                    'type' => 'range',
                    'min' => 1,
                    'max' => 5,
                    'step' => 1,
                    'default_value' => 5,
                    'wrapper' => ['width' => '33'],
                ],
                [
                    'key' => 'field_text',
                    'label' => 'Témoignage',
                    'name' => 'text',
                    'type' => 'textarea',
                    'rows' => 3,
                ],
            ],
        ],
        [
            'key' => 'field_autoplay',
            'label' => 'Autoplay',
            'name' => 'autoplay',
            'type' => 'true_false',
            'ui' => 1,
            'default_value' => 1,
            'wrapper' => ['width' => '25'],
        ],
    ],
    'location' => [
        [
            [
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/testimonial-slider',
            ],
        ],
    ],
]);
