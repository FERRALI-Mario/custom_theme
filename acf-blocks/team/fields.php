<?php

if (!function_exists('acf_add_local_field_group')) {
    return;
}

acf_add_local_field_group([
    'key' => 'group_team_block',
    'title' => 'Bloc Équipe',
    'fields' => [
        [
            'key' => 'field_team_members',
            'label' => 'Membres de l’équipe',
            'name' => 'members',
            'type' => 'repeater',
            'layout' => 'block',
            'min' => 1,
            'button_label' => 'Ajouter un membre',
            'sub_fields' => [
                [
                    'key' => 'field_team_photo',
                    'label' => 'Photo',
                    'name' => 'photo',
                    'type' => 'image',
                    'return_format' => 'array',
                    'preview_size' => 'thumbnail',
                    'library' => 'all',
                    'wrapper' => ['width' => '33'],
                ],
                [
                    'key' => 'field_team_name',
                    'label' => 'Nom',
                    'name' => 'name',
                    'type' => 'text',
                    'wrapper' => ['width' => '33'],
                ],
                [
                    'key' => 'field_team_role',
                    'label' => 'Rôle',
                    'name' => 'role',
                    'type' => 'text',
                    'wrapper' => ['width' => '33'],
                ],
                [
                    'key' => 'field_team_bio',
                    'label' => 'Bio courte',
                    'name' => 'bio',
                    'type' => 'textarea',
                    'rows' => 3,
                ],
            ],
        ],
    ],
    'location' => [
        [
            [
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/team',
            ],
        ],
    ],
]);
