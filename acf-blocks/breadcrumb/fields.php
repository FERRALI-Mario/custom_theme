<?php

if (function_exists('acf_add_local_field_group')) :

    acf_add_local_field_group([
        'key' => 'group_breadcrumb_block',
        'title' => 'Fil d’Ariane',
        'fields' => [
            [
                'key' => 'field_breadcrumb_enabled',
                'label' => 'Afficher le fil d’Ariane',
                'name' => 'enabled',
                'type' => 'true_false',
                'default_value' => 1,
                'ui' => 1,
            ]
        ],
        'location' => [
            [
                [
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/breadcrumb',
                ],
            ],
        ],
        'active' => true,
    ]);

endif;
