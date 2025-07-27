<?php

if (function_exists('acf_add_local_field_group')) :

    acf_add_local_field_group([
        'key' => 'number_counter',
        'title' => 'Bloc compteur de chiffres',
        'fields' => [
            [
                'key' => 'field_counters',
                'label' => 'Compteurs',
                'name' => 'counters',
                'type' => 'repeater',
                'button_label' => 'Ajouter un compteur',
                'sub_fields' => [
                    [
                        'key' => 'field_number',
                        'label' => 'Nombre',
                        'name' => 'number',
                        'type' => 'number',
                        'required' => 1,
                    ],
                    [
                        'key' => 'field_title',
                        'label' => 'Titre',
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
                'min' => 1,
                'max' => 4,
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
