<?php

if (function_exists('acf_add_local_field_group')) :

    acf_add_local_field_group([
        'key' => 'product_filter_fields',
        'title' => 'Filtres de produits',
        'fields' => [
            [
                'key' => 'field_product_category',
                'label' => 'Catégories de produits',
                'name' => 'product_category',
                'type' => 'taxonomy',
                'taxonomy' => 'product_cat',
                'field_type' => 'multi_select',
                'multiple' => 1,
                'return_format' => 'id',
                'add_term' => 0,
                'allow_null' => 1,
            ],
            [
                'key' => 'field_price_min',
                'label' => 'Prix minimum',
                'name' => 'price_min',
                'type' => 'number',
                'min' => 0,
                'step' => 1,
            ],
            [
                'key' => 'field_price_max',
                'label' => 'Prix maximum',
                'name' => 'price_max',
                'type' => 'number',
                'min' => 0,
                'step' => 1,
            ],
            [
                'key' => 'field_custom_filters',
                'label' => 'Filtres personnalisés',
                'name' => 'custom_filters',
                'type' => 'repeater',
                'sub_fields' => [
                    [
                        'key' => 'field_filter_label',
                        'label' => 'Label',
                        'name' => 'label',
                        'type' => 'text',
                        'required' => 1,
                    ],
                    [
                        'key' => 'field_filter_name',
                        'label' => 'Paramètre GET',
                        'name' => 'name',
                        'type' => 'text',
                        'required' => 1,
                    ],
                ],
                'min' => 0,
                'layout' => 'row',
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/product-filter',
                ]
            ]
        ],
    ]);
endif;
