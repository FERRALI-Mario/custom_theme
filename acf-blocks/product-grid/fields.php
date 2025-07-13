<?php

if (!function_exists('acf_add_local_field_group')) {
    return;
}

acf_add_local_field_group([
    'key' => 'group_product_grid',
    'title' => 'Bloc Grille de Produits',
    'fields' => [
        [
            'key' => 'field_product_grid_items',
            'label' => 'Produits',
            'name' => 'products',
            'type' => 'repeater',
            'min' => 1,
            'layout' => 'row',
            'button_label' => 'Ajouter un produit',
            'sub_fields' => [
                [
                    'key' => 'field_pg_image',
                    'label' => 'Image',
                    'name' => 'image',
                    'type' => 'image',
                    'return_format' => 'array',
                    'preview_size' => 'medium',
                    'library' => 'all',
                    'wrapper' => ['width' => '50'],
                ],
                [
                    'key' => 'field_pg_title',
                    'label' => 'Titre',
                    'name' => 'title',
                    'type' => 'text',
                    'wrapper' => ['width' => '50'],
                ],
                [
                    'key' => 'field_pg_price',
                    'label' => 'Prix',
                    'name' => 'price',
                    'type' => 'number',
                    'step' => '0.01',
                    'prepend' => '€',
                    'wrapper' => ['width' => '50'],
                ],
                [
                    'key' => 'field_pg_description',
                    'label' => 'Description',
                    'name' => 'description',
                    'type' => 'textarea',
                    'rows' => 3,
                    'wrapper' => ['width' => '50'],
                ],
            ],
        ],
        [
            'key' => 'field_pg_columns',
            'label' => 'Nombre de colonnes',
            'name' => 'columns',
            'type' => 'select',
            'choices' => [
                'grid-cols-1' => '1',
                'grid-cols-2' => '2',
                'grid-cols-3' => '3',
                'grid-cols-4' => '4',
            ],
            'default_value' => 'grid-cols-3',
            'wrapper' => ['width' => '25'],
        ],
        [
            'key' => 'field_pg_padding',
            'label' => 'Espacement interne',
            'name' => 'padding',
            'type' => 'select',
            'choices' => [
                'p-4' => 'Petit',
                'p-6' => 'Moyen',
                'p-8' => 'Grand',
            ],
            'default_value' => 'p-6',
            'wrapper' => ['width' => '25'],
        ],
        [
            'key' => 'field_pg_bg',
            'label' => 'Couleur de fond',
            'name' => 'background',
            'type' => 'select',
            'choices' => [
                'bg-white' => 'Blanc',
                'bg-gray-100' => 'Gris clair',
                'bg-blue-50' => 'Bleu pâle',
            ],
            'default_value' => 'bg-white',
            'wrapper' => ['width' => '25'],
        ],
        [
            'key' => 'field_pg_align',
            'label' => 'Alignement du contenu',
            'name' => 'align',
            'type' => 'radio',
            'choices' => [
                'text-left' => 'Gauche',
                'text-center' => 'Centré',
                'text-right' => 'Droite',
            ],
            'default_value' => 'text-left',
            'layout' => 'horizontal',
            'wrapper' => ['width' => '25'],
        ],
    ],
    'location' => [
        [
            [
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/product-grid',
            ],
        ],
    ],
    'style' => 'default',
    'position' => 'acf_after_title',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
]);
