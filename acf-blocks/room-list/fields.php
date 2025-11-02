<?php
if (function_exists('acf_add_local_field_group')) :
    acf_add_local_field_group([
        'key' => 'group_room_list_fields',
        'title' => 'Liste des pièces / espaces',
        'fields' => [
            [
                'key' => 'field_room_list_title',
                'label' => 'Titre',
                'name' => 'title',
                'type' => 'text',
                'required' => 1,
            ],
            [
                'key' => 'field_room_list_subtitle',
                'label' => 'Sous-titre',
                'name' => 'subtitle',
                'type' => 'textarea',
                'rows' => 2,
                'new_lines' => '',
                'required' => 1,
            ],
            [
                'key' => 'field_room_list_rooms',
                'label' => 'Pièces / espaces',
                'name' => 'rooms',
                'type' => 'repeater',
                'layout' => 'row',
                'collapsed' => 'field_room_list_room_type',
                'button_label' => 'Ajouter une pièce / un espace',
                'sub_fields' => [
                    [
                        'key' => 'field_room_list_room_type',
                        'label' => 'Type (ex. Chambre, Salon, Cuisine…)',
                        'name' => 'room_type',
                        'type' => 'text',
                        'required' => 1,
                        'placeholder' => 'Chambre, Salon, Cuisine…',
                    ],
                    [
                        'key' => 'field_room_list_room_area',
                        'label' => 'Surface (m²)',
                        'name' => 'area_sqm',
                        'type' => 'number',
                        'min' => 0,
                        'step' => 0.5,
                    ],
                    [
                        'key' => 'field_room_list_room_description',
                        'label' => 'Description',
                        'name' => 'description',
                        'type' => 'textarea',
                        'rows' => 3,
                        'new_lines' => '',
                    ],
                    [
                        'key' => 'field_room_list_room_photos',
                        'label' => 'Photos (1 à 3)',
                        'name' => 'photos',
                        'type' => 'gallery',
                        'min' => 1,
                        'max' => 3,
                        'return_format' => 'array',
                        'preview_size' => 'medium',
                        'instructions' => 'Ajoutez 1 à 3 photos. Elles s’afficheront en carrousel.',
                    ],
                ],
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/room-list',
                ],
            ],
        ],
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'active' => true,
    ]);
endif;
