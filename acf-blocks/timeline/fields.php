<?php

if (function_exists('acf_add_local_field_group')) :

    acf_add_local_field_group([
        'key' => 'timeline',
        'title' => 'Frise chronologique',
        'fields' => [
            [
                'key' => 'field_title',
                'label' => 'Titre',
                'name' => 'title',
                'type' => 'text',
                'instructions' => 'Titre affiché au-dessus de la frise chronologique',
                'required' => 0,
                'wrapper' => ['width' => '100'],
            ],
            [
                'key' => 'field_subtitle',
                'label' => 'Sous-titre',
                'name' => 'subtitle',
                'type' => 'textarea',
                'instructions' => 'Description optionnelle affichée sous le titre',
                'rows' => 2,
                'wrapper' => ['width' => '100'],
            ],
            [
                'key' => 'field_events',
                'label' => 'Événements',
                'name' => 'events',
                'type' => 'repeater',
                'button_label' => 'Ajouter un événement',
                'min' => 1,
                'max' => 10,
                'sub_fields' => [
                    [
                        'key' => 'field_year',
                        'label' => 'Année',
                        'name' => 'year',
                        'type' => 'number',
                        'required' => 1,
                    ],
                    [
                        'key' => 'field_event_title',
                        'label' => 'Titre de l\'événement',
                        'name' => 'event_title',
                        'type' => 'text',
                        'required' => 1,
                    ],
                    [
                        'key' => 'field_event_description',
                        'label' => 'Description de l\'événement',
                        'name' => 'event_description',
                        'type' => 'textarea',
                        'required' => 0,
                    ],
                ],
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/timeline',
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
