<?php

if (function_exists('acf_add_local_field_group')) :

acf_add_local_field_group([
    'key' => 'group_number_counter',
    'title' => 'Number Counter',
    'fields' => [
        [
            'key' => 'field_counters',
            'label' => 'Counters',
            'name' => 'counters',
            'type' => 'repeater',
            'button_label' => 'Add Counter',
            'sub_fields' => [
                [
                    'key' => 'field_number',
                    'label' => 'Number',
                    'name' => 'number',
                    'type' => 'number',
                    'required' => 1,
                ],
                [
                    'key' => 'field_title',
                    'label' => 'Title',
                    'name' => 'title',
                    'type' => 'text',
                    'required' => 1,
                ],
                [
                    'key' => 'field_description',
                    'label' => 'Description',
                    'name' => 'description',
                    'type' => 'textarea',
                    'required' => 0,
                ],
            ],
            'min' => 1, // Au moins un compteur
            'max' => 4, // Maximum 4 compteurs
        ],
    ],
    'location' => [
        [
            [
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/number-counter',
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
