<?php

if (function_exists('acf_add_local_field_group')) :

acf_add_local_field_group([
    'key' => 'group_quote',
    'title' => 'Quote Block',
    'fields' => [
        [
            'key' => 'field_quote_content',
            'label' => 'Citation',
            'name' => 'quote_content',
            'type' => 'textarea',
            'required' => 1,
            'rows' => 3,
        ],
        [
            'key' => 'field_quote_author',
            'label' => 'Auteur',
            'name' => 'quote_author',
            'type' => 'text',
            'required' => 1,
        ],
    ],
    'location' => [
        [
            [
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/quote',
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
