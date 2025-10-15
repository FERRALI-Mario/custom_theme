<?php

use App\Helpers\ProductHelpers;

if (function_exists('acf_add_local_field_group')) :
    acf_add_local_field_group([
        'key' => 'group_product_grid',
        'title' => 'Bloc Grille de produits',
        'fields' => [
            [
                'key' => 'field_tab_content',
                'label' => 'Contenu',
                'type' => 'tab',
                'placement' => 'top',
            ],
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
                'return_format' => 'id',
                'allow_null' => true,
            ],
            [
                'key' => 'field_tab_style',
                'label' => 'Options',
                'type' => 'tab',
                'placement' => 'top',
            ],
            [
                'key' => 'field_product_grid_show_breadcrumb',
                'label' => 'Afficher le fil d\'Ariane',
                'name' => 'show_breadcrumb',
                'type' => 'true_false',
                'ui' => 1,
                'default_value' => 1,
            ],
            [
                'key' => 'field_product_grid_enable_filter_toggle',
                'label' => 'Activer les filtres produits',
                'name' => 'enable_filter',
                'type' => 'true_false',
                'ui' => 1,
                'default_value' => 0,
            ],
            [
                'key' => 'field_product_grid_filter_builder',
                'label' => 'Filtres personnalisés',
                'name' => 'product_filters',
                'type' => 'repeater',
                'layout' => 'block',
                'button_label' => 'Ajouter un filtre',
                'conditional_logic' => [
                    [
                        [
                            'field' => 'field_product_grid_enable_filter_toggle',
                            'operator' => '==',
                            'value' => '1',
                        ]
                    ]
                ],
                'sub_fields' => [
                    [
                        'key' => 'field_filter_type',
                        'label' => 'Type de filtre',
                        'name' => 'type',
                        'type' => 'select',
                        'choices' => ProductHelpers::getFilterChoices(),
                        'required' => 1,
                    ]
                ]
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
        'position' => 'acf_after_title',
        'style' => 'default',
        'active' => true,
    ]);
endif;
