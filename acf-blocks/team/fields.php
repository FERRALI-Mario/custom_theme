<?php

if (function_exists('acf_add_local_field_group')) :

acf_add_local_field_group([
    'key' => 'group_team',
    'title' => 'Team Block',
    'fields' => [
        [
            'key' => 'field_team_title',
            'label' => 'Titre',
            'name' => 'team_title',
            'type' => 'text',
            'required' => 1,
        ],
        [
            'key' => 'field_team_subtitle',
            'label' => 'Sous-titre',
            'name' => 'team_subtitle',
            'type' => 'textarea',
            'rows' => 3,
            'required' => 0,
        ],
        [
            'key' => 'field_team_members',
            'label' => 'Membres de l\'équipe',
            'name' => 'team_members',
            'type' => 'repeater',
            'button_label' => 'Ajouter un membre',
            'sub_fields' => [
                [
                    'key' => 'field_member_photo',
                    'label' => 'Photo du membre',
                    'name' => 'member_photo',
                    'type' => 'image',
                    'return_format' => 'array',
                    'required' => 1,
                ],
                [
                    'key' => 'field_member_name',
                    'label' => 'Nom du membre',
                    'name' => 'member_name',
                    'type' => 'text',
                    'required' => 1,
                ],
                [
                    'key' => 'field_member_role',
                    'label' => 'Rôle du membre',
                    'name' => 'member_role',
                    'type' => 'text',
                    'required' => 1,
                ],
                [
                    'key' => 'field_member_linkedin',
                    'label' => 'Lien LinkedIn',
                    'name' => 'member_linkedin',
                    'type' => 'url',
                    'required' => 0,
                ],
            ],
            'min' => 1,
            'max' => 6, // Limiter à 6 membres
        ],
    ],
    'location' => [
        [
            [
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/team',
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
