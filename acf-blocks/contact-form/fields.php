<?php

if (function_exists('acf_add_local_field_group')) :

    acf_add_local_field_group([
        'key' => 'contact_form',
        'title' => 'Block formulaire de contact',
        'fields' => [
            [
                'key' => 'field_contact_form_title',
                'label' => 'Titre',
                'name' => 'title',
                'type' => 'text',
                'required' => 1,
            ],
            [
                'key' => 'field_contact_form_subtitle',
                'label' => 'Sous-titre',
                'name' => 'subtitle',
                'type' => 'textarea',
                'rows' => 3,
                'required' => 1,
            ],
            [
                'key' => 'field_contact_fields',
                'label' => 'Champs du formulaire',
                'name' => 'form_fields',
                'type' => 'repeater',
                'button_label' => 'Ajouter un champ',
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_input_type',
                        'label' => 'Type de champ',
                        'name' => 'type',
                        'type' => 'select',
                        'choices' => [
                            'text' => 'Texte simple (Nom, Prénom)',
                            'email' => 'Email',
                            'tel' => 'Téléphone',
                            'textarea' => 'Message (Zone de texte)',
                        ],
                        'default_value' => 'text',
                    ],
                    [
                        'key' => 'field_input_label',
                        'label' => 'Libellé (Label)',
                        'name' => 'label',
                        'type' => 'text',
                        'required' => 1,
                        'placeholder' => 'Ex: Votre nom',
                    ],
                ],
            ],
            [
                'key' => 'field_contact_btn_text',
                'label' => 'Texte du bouton',
                'name' => 'submit_text',
                'type' => 'text',
                'default_value' => 'Envoyer le message',
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/contact-form',
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
