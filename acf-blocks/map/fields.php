<?php

if (function_exists('acf_add_local_field_group')) :

    acf_add_local_field_group([
        'key' => 'map',
        'title' => 'Bloc carte',
        'fields' => [
            [
                'key' => 'field_map_label',
                'label' => 'Nom de la boutique',
                'name' => 'map_label',
                'type' => 'text',
                'required' => 0,
                'wrapper' => [
                    'width' => '100',
                ]
            ],
            [
                'key' => 'field_map_api_key',
                'label' => 'Clé API Google Maps',
                'name' => 'map_api_key',
                'type' => 'text',
                'instructions' => 'Copiez votre clé API Google Maps ici (commence par AIza...).',
                'required' => 1,
                'placeholder' => 'AIzaSy...',
            ],
            [
                'key' => 'field_map_latitude',
                'label' => 'Latitude',
                'name' => 'map_latitude',
                'type' => 'number',
                'required' => 1,
                'step' => 0.000001,
                'min' => -90,
                'max' => 90,
            ],
            [
                'key' => 'field_map_longitude',
                'label' => 'Longitude',
                'name' => 'map_longitude',
                'type' => 'number',
                'required' => 1,
                'step' => 0.000001,
                'min' => -180,
                'max' => 180,
            ],
            [
                'key' => 'field_map_zoom',
                'label' => 'Zoom',
                'name' => 'map_zoom',
                'type' => 'number',
                'default_value' => 14,
                'min' => 1,
                'max' => 18,
                'step' => 1,
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/map',
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
