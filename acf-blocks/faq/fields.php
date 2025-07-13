<?php

if (!function_exists('acf_add_local_field_group')) {
    return;
}

acf_add_local_field_group([
    'key' => 'group_faq_block',
    'title' => 'Bloc FAQ',
    'fields' => [
        [
            'key' => 'field_faq_items',
            'label' => 'Questions/Réponses',
            'name' => 'faq_items',
            'type' => 'repeater',
            'button_label' => 'Ajouter une question',
            'min' => 1,
            'layout' => 'row',
            'sub_fields' => [
                [
                    'key' => 'field_question',
                    'label' => 'Question',
                    'name' => 'question',
                    'type' => 'text',
                    'wrapper' => ['width' => '50'],
                ],
                [
                    'key' => 'field_answer',
                    'label' => 'Réponse',
                    'name' => 'answer',
                    'type' => 'textarea',
                    'rows' => 3,
                    'wrapper' => ['width' => '50'],
                ],
            ],
        ],
    ],
    'location' => [
        [
            [
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/faq',
            ],
        ],
    ],
]);
