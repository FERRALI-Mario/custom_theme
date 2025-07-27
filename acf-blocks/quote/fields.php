<?php

if (function_exists('acf_add_local_field_group')) :

    acf_add_local_field_group([
        'key' => 'quote',
        'title' => 'Bloc citation',
        'fields' => [
            [
                'key' => 'field_quote_content',
                'label' => 'Citation',
                'name' => 'content',
                'type' => 'textarea',
                'required' => 1,
                'rows' => 3,
            ],
            [
                'key' => 'field_quote_author',
                'label' => 'Auteur',
                'name' => 'author',
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
