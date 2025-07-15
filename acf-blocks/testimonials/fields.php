<?php

if (function_exists('acf_add_local_field_group')) :

acf_add_local_field_group([
    'key' => 'group_testimonials',
    'title' => 'Testimonials Block',
    'fields' => [
        [
            'key' => 'field_testimonials_title',
            'label' => 'Titre',
            'name' => 'title',
            'type' => 'text',
            'required' => 0,
        ],
        [
            'key' => 'field_testimonials_intro',
            'label' => 'Texte d’introduction',
            'name' => 'intro',
            'type' => 'textarea',
            'rows' => 3,
            'required' => 0,
        ],
        [
            'key' => 'field_testimonials',
            'label' => 'Témoignages',
            'name' => 'testimonials',
            'type' => 'repeater',
            'required' => 1,
            'sub_fields' => [
                [
                    'key' => 'field_client_name',
                    'label' => 'Nom du client',
                    'name' => 'client_name',
                    'type' => 'text',
                    'required' => 1,
                ],
                [
                    'key' => 'field_testimonial_text',
                    'label' => 'Témoignage',
                    'name' => 'testimonial_text',
                    'type' => 'textarea',
                    'required' => 1,
                    'rows' => 4,
                ],
                [
                    'key' => 'field_client_photo',
                    'label' => 'Photo du client',
                    'name' => 'client_photo',
                    'type' => 'image',
                    'return_format' => 'array',
                    'required' => 1,
                ],
                [
                    'key' => 'field_client_job',
                    'label' => 'Job ou Entreprise',
                    'name' => 'client_job',
                    'type' => 'text',
                    'required' => 0,
                ],
                [
                    'key' => 'field_rating',
                    'label' => 'Note',
                    'name' => 'rating',
                    'type' => 'number',
                    'min' => 1,
                    'max' => 5,
                    'required' => 1,
                ],
            ],
            'min' => 1,
            'max' => 6,
            'button_label' => 'Ajouter un témoignage',
        ],
    ],
    'location' => [
        [
            [
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/testimonials',
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
