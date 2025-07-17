<?php

if (function_exists('acf_add_local_field_group')) :

acf_add_local_field_group([
    'key' => 'group_contact_infos',
    'title' => 'Contact Infos Block',
    'fields' => [
        [
            'key'   => 'field_contact_form_title',
            'label' => 'Titre du bloc',
            'name'  => 'contact_form_title',
            'type'  => 'text',
        ],
        [
            'key'   => 'field_contact_form_text',
            'label' => 'Texte introductif',
            'name'  => 'contact_form_text',
            'type'  => 'textarea',
            'rows'  => 3,
        ],
        [
            'key'           => 'field_contact_items',
            'label'         => 'Informations de contact',
            'name'          => 'contact_items',
            'type'          => 'repeater',
            'button_label'  => 'Ajouter une info de contact',
            'min'           => 1,
            'max'           => 4,
            'layout'        => 'table',
            'sub_fields'    => [
                [
                    'key'     => 'field_contact_type',
                    'label'   => 'Type',
                    'name'    => 'type',
                    'type'    => 'select',
                    'choices' => [
                        'adresse'  => 'Adresse',
                        'telephone'=> 'Téléphone',
                        'email'    => 'Email',
                        'horaire'  => 'Horaire',
                    ],
                    'required' => 1,
                ],
                [
                    'key'      => 'field_contact_value',
                    'label'    => 'Valeur',
                    'name'     => 'value',
                    'type'     => 'text',
                    'required' => 1,
                ],
            ],
        ],
    ],
    'location' => [
        [
            [
                'param'    => 'block',
                'operator' => '==',
                'value'    => 'acf/contact-infos',
            ],
        ],
    ],
]);

endif;
