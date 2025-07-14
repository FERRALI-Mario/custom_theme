<?php

if (function_exists('acf_add_local_field_group')) :

acf_add_local_field_group([
    'key' => 'group_features_list',
    'title' => 'Liste de Fonctionnalités',
    'fields' => [
        [
            'key' => 'field_features_list',
            'label' => 'Fonctionnalités',
            'name' => 'features_list',
            'type' => 'repeater',
            'sub_fields' => [
                [
                    'key' => 'field_feature_icon',
                    'label' => 'Icône',
                    'name' => 'feature_icon',
                    'type' => 'image',
                    'return_format' => 'url',
                    'preview_size' => 'thumbnail',
                    'required' => 1,
                ],
                [
                    'key' => 'field_feature_title',
                    'label' => 'Titre',
                    'name' => 'feature_title',
                    'type' => 'text',
                    'required' => 1,
                ],
                [
                    'key' => 'field_feature_description',
                    'label' => 'Description',
                    'name' => 'feature_description',
                    'type' => 'textarea',
                    'rows' => 3,
                    'required' => 1,
                ],
            ],
            'min' => 1,
            'max' => 10, // Limite le nombre d'items
            'layout' => 'table', // Style d'affichage
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
