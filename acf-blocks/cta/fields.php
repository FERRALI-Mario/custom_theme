<?php

if (function_exists('acf_add_local_field_group')) :

acf_add_local_field_group([
    'key' => 'group_cta_block',
    'title' => 'Bloc Call to Action',
    'fields' => [
        [
            'key' => 'field_cta_title',
            'label' => 'Titre',
            'name' => 'cta_title',
            'type' => 'text',
            'required' => 1, // Titre obligatoire
        ],
        [
            'key' => 'field_cta_text',
            'label' => 'Texte descriptif',
            'name' => 'cta_text',
            'type' => 'textarea',
            'rows' => 4,
        ],
        [
            'key' => 'field_cta_button_text',
            'label' => 'Texte du bouton',
            'name' => 'cta_button_text',
            'type' => 'text',
            'required' => 1, // Texte du bouton obligatoire
        ],
        [
            'key' => 'field_cta_button_url',
            'label' => 'Lien du bouton',
            'name' => 'cta_button_url',
            'type' => 'url',
            'required' => 1, // Lien du bouton obligatoire
        ],
        [
            'key' => 'field_cta_open_in_new_tab',
            'label' => 'Ouvrir dans un nouvel onglet',
            'name' => 'cta_open_in_new_tab',
            'type' => 'true_false',
            'ui' => 1,
            'default_value' => 0,
        ],
        [
            'key' => 'field_cta_background',
            'label' => 'Image de fond (obligatoire)',
            'name' => 'cta_background',
            'type' => 'image',
            'return_format' => 'url',
            'preview_size' => 'medium',
            'required' => 1, // Image obligatoire
        ],
        [
            'key' => 'field_cta_text_color',
            'label' => 'Couleur du texte',
            'name' => 'cta_text_color',
            'type' => 'color_picker', // Sélecteur de couleur pour le texte
            'default_value' => '#ffffff', // Couleur par défaut : blanc
        ],
    ],
    'location' => [
        [
            [
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/cta',
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
