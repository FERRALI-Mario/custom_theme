<?php

if (function_exists('acf_add_local_field_group')) :

    acf_add_local_field_group([
        'key' => 'contact_infos',
        'title' => 'Block informations de contact',
        'fields' => [
            [
                'key'   => 'field_contact_infos_title',
                'label' => 'Titre',
                'name'  => 'title',
                'type'  => 'text',
            ],
            [
                'key'   => 'field_contact_infos_subtitle',
                'label' => 'Sous-titre',
                'name'  => 'subtitle',
                'type'  => 'textarea',
                'rows'  => 3,
            ],
            [
                'key'           => 'field_contact_infos_items',
                'label'         => 'Informations de contact',
                'name'          => 'contact_items',
                'type'          => 'repeater',
                'button_label'  => 'Ajouter une info de contact',
                'min'           => 1,
                'max'           => 4,
                'layout'        => 'table',
                'sub_fields'    => [
                    [
                        'key'     => 'field_contact_infos_type',
                        'label'   => 'Type',
                        'name'    => 'type',
                        'type'    => 'select',
                        'choices' => [
                            'adresse'  => 'Adresse',
                            'telephone' => 'Téléphone',
                            'email'    => 'Email',
                            'horaire'  => 'Horaire',
                        ],
                        'required' => 1,
                    ],
                    [
                        'key'      => 'field_contact_infos_value',
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
