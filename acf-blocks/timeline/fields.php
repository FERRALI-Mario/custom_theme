<?php

if (function_exists('acf_add_local_field_group')) :

    acf_add_local_field_group([
        'key' => 'group_timeline',
        'title' => 'Timeline',
        'fields' => [
            [
                'key' => 'field_title',
                'label' => 'Titre principal',
                'name' => 'title',
                'type' => 'text',
                'instructions' => 'Titre affiché au-dessus de la roadmap',
                'required' => 0,
                'wrapper' => ['width' => '100'],
            ],
            [
                'key' => 'field_description',
                'label' => 'Description',
                'name' => 'paragraph',
                'type' => 'textarea',
                'instructions' => 'Description optionnelle affichée sous le titre',
                'rows' => 2,
                'wrapper' => ['width' => '100'],
            ],
            [
                'key' => 'field_events',
                'label' => 'Events',
                'name' => 'events',
                'type' => 'repeater',
                'button_label' => 'Add Event',
                'min' => 1,
                'max' => 10,
                'sub_fields' => [
                    [
                        'key' => 'field_year',
                        'label' => 'Year',
                        'name' => 'year',
                        'type' => 'number',
                        'required' => 1,
                    ],
                    [
                        'key' => 'field_event_title',
                        'label' => 'Title',
                        'name' => 'event_title',
                        'type' => 'text',
                        'required' => 1,
                    ],
                    [
                        'key' => 'field_event_description',
                        'label' => 'Description',
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
