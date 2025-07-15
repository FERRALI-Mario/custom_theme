<?php

if (function_exists('acf_add_local_field_group')) :

acf_add_local_field_group([
    'key' => 'group_contact_infos',
    'title' => 'Contact Infos Block',
    'fields' => [
        [
            'key' => 'field_contact_form_title',
            'label' => 'Titre du bloc',
            'name' => 'contact_form_title',
            'type' => 'text',
            'required' => 0,
            'wrapper' => [
                'width' => '100',
            ],
        ],
        [
            'key' => 'field_contact_form_text',
            'label' => 'Texte introductif',
            'name' => 'contact_form_text',
            'type' => 'textarea',
            'required' => 0,
            'rows' => 3,
            'wrapper' => [
                'width' => '100',
            ],
        ],
        [
            'key' => 'field_contact_address',
            'label' => 'Adresse',
            'name' => 'contact_address',
            'type' => 'text',
            'required' => 1,
            'wrapper' => [
                'width' => '100',
            ],
        ],
        [
            'key' => 'field_contact_phone',
            'label' => 'Numéro de téléphone',
            'name' => 'contact_phone',
            'type' => 'text',
            'required' => 0,
            'wrapper' => [
                'width' => '100',
            ],
        ],
        [
            'key' => 'field_contact_email',
            'label' => 'Email',
            'name' => 'contact_email',
            'type' => 'email',
            'required' => 0,
            'wrapper' => [
                'width' => '100',
            ],
        ],
    ],
    'location' => [
        [
            [
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/contact-infos',
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
