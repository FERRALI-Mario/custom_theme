<?php

if (!function_exists('acf_add_local_field_group')) {
    return;
}

acf_add_local_field_group([
    'key' => 'group_contact_form',
    'title' => 'Bloc Formulaire de contact',
    'fields' => [
        [
            'key' => 'field_contact_title',
            'label' => 'Titre du formulaire',
            'name' => 'form_title',
            'type' => 'text',
        ],
    ],
    'location' => [
        [
            [
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/contact-form',
            ],
        ],
    ],
]);
