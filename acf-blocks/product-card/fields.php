<?php

if (!function_exists('acf_add_local_field_group')) {
    return;
}

acf_add_local_field_group([
    'key' => 'group_product_card',
    'title' => 'Bloc Product Card',
    'fields' => [
        [
            'key' => 'field_product_image',
            'label' => 'Image du produit',
            'name' => 'product_image',
            'type' => 'image',
            'return_format' => 'array',
            'preview_size' => 'medium',
            'library' => 'all',
            'wrapper' => ['width' => '50'],
        ],
        [
            'key' => 'field_product_title',
            'label' => 'Titre du produit',
            'name' => 'product_title',
            'type' => 'text',
            'required' => 1,
            'wrapper' => ['width' => '50'],
        ],
        [
            'key' => 'field_product_price',
            'label' => 'Prix',
            'name' => 'product_price',
            'type' => 'number',
            'prepend' => '€',
            'step' => '0.01',
            'wrapper' => ['width' => '50'],
        ],
        [
            'key' => 'field_product_description',
            'label' => 'Description courte',
            'name' => 'product_description',
            'type' => 'textarea',
            'rows' => 3,
            'wrapper' => ['width' => '50'],
        ],
        [
            'key' => 'field_product_align',
            'label' => 'Alignement du texte',
            'name' => 'product_align',
            'type' => 'radio',
            'choices' => [
                'left' => 'Gauche',
                'center' => 'Centré',
                'right' => 'Droite',
            ],
            'layout' => 'horizontal',
            'default_value' => 'left',
            'wrapper' => ['width' => '50'],
        ],
        [
            'key' => 'field_product_padding',
            'label' => 'Espacement interne',
            'name' => 'product_padding',
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
            'key' => 'field_product_bg',
            'label' => 'Couleur de fond',
            'name' => 'product_bg',
            'type' => 'select',
            'choices' => [
                'bg-white' => 'Blanc',
                'bg-gray-100' => 'Gris clair',
                'bg-blue-50' => 'Bleu pâle',
            ],
            'default_value' => 'bg-white',
            'wrapper' => ['width' => '25'],
        ],
    ],
    'location' => [
        [
            [
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/product-card',
            ],
        ],
    ],
    'style' => 'default',
    'position' => 'acf_after_title',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
]);
