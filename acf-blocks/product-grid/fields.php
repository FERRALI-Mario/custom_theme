<?php

if (function_exists('acf_add_local_field_group')) :
    acf_add_local_field_group([
        'key' => 'product_grid',
        'title' => 'Bloc grille de produits',
        'fields' => [
            [
                'key' => 'field_product_grid_title',
                'label' => 'Titre',
                'name' => 'title',
                'type' => 'text',
                'required' => 1,
            ],
            [
                'key' => 'field_product_grid_description',
                'label' => 'Description',
                'name' => 'description',
                'type' => 'textarea',
                'required' => 1,
            ],
            [
                'key' => 'field_products_per_page',
                'label' => 'Nombre de produits par page',
                'name' => 'products_per_page',
                'type' => 'number',
                'default_value' => 12,
                'min' => 4,
                'max' => 40,
                'step' => 4,
            ],
            [
                'key' => 'field_product_category',
                'label' => 'Catégorie de produits',
                'name' => 'product_category',
                'type' => 'taxonomy',
                'taxonomy' => 'product_cat',
                'field_type' => 'select',
                'allow_null' => true,
                'add_term' => false,
                'save_terms' => true,
                'multiple' => false,
                'return_format' => 'id',
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/product-grid',
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
