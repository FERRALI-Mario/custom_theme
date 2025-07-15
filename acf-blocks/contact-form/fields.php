<?php

if (function_exists('acf_add_local_field_group')) :

acf_add_local_field_group([
    'key' => 'group_contact_form',
    'title' => 'Contact Form Block',
    'fields' => [
        [
            'key' => 'field_contact_form_shortcode',
            'label' => 'Formulaire de contact (Shortcode)',
            'name' => 'contact_form_shortcode',
            'type' => 'text',
            'instructions' => 'Entrez le shortcode du formulaire de contact (par exemple [contact-form-7 id="123" title="Contact form 1"])',
            'required' => 0,
        ],
        [
            'key' => 'field_contact_form_title',
            'label' => 'Titre',
            'name' => 'contact_form_title',
            'type' => 'text',
            'required' => 0,
        ],
        [
            'key' => 'field_contact_form_text',
            'label' => 'Texte introductif',
            'name' => 'contact_form_text',
            'type' => 'textarea',
            'rows' => 3,
            'required' => 0,
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
