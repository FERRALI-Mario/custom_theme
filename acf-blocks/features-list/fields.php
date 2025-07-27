<?php

if (function_exists('acf_add_local_field_group')) :

    acf_add_local_field_group([
        'key' => 'features_list',
        'title' => 'Block liste de Fonctionnalités',
        'fields' => [
            [
                'key' => 'field_features_list',
                'label' => 'Fonctionnalités',
                'name' => 'features_list',
                'type' => 'repeater',
                'sub_fields' => [
                    [
                        'key' => 'field_features_list_icon',
                        'label' => 'Icône',
                        'name' => 'icon',
                        'type' => 'image',
                        'return_format' => 'url',
                        'preview_size' => 'thumbnail',
                        'required' => 1,
                    ],
                    [
                        'key' => 'field_features_list_title',
                        'label' => 'Titre',
                        'name' => 'title',
                        'type' => 'text',
                        'required' => 1,
                    ],
                    [
                        'key' => 'field_features_list_description',
                        'label' => 'Description',
                        'name' => 'description',
                        'type' => 'textarea',
                        'rows' => 3,
                        'required' => 1,
                    ],
                ],
                'min' => 1,
                'max' => 10,
                'layout' => 'table',
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/features-list',
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
