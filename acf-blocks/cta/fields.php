<?php

if (function_exists('acf_add_local_field_group')) :

    acf_add_local_field_group([
        'key' => 'cta',
        'title' => 'Block appel à l\'action',
        'fields' => [
            [
                'key' => 'field_cta_title',
                'label' => 'Titre',
                'name' => 'title',
                'type' => 'text',
                'required' => 1,
            ],
            [
                'key' => 'field_cta_subtitle',
                'label' => 'Sous-titre',
                'name' => 'subtitle',
                'type' => 'textarea',
                'rows' => 4,
            ],
            [
                'key' => 'field_cta_button_text',
                'label' => 'Texte du bouton',
                'name' => 'button_text',
                'type' => 'text',
                'required' => 1,
            ],
            [
                'key' => 'field_cta_button_url',
                'label' => 'Lien du bouton',
                'name' => 'button_url',
                'type' => 'url',
                'required' => 1,
            ],
            [
                'key' => 'field_cta_open_in_new_tab',
                'label' => 'Ouvrir dans un nouvel onglet',
                'name' => 'open_in_new_tab',
                'type' => 'true_false',
                'ui' => 1,
                'default_value' => 0,
            ],
            [
                'key' => 'field_cta_background',
                'label' => 'Image de fond',
                'name' => 'background',
                'type' => 'image',
                'return_format' => 'url',
                'preview_size' => 'medium',
                'required' => 1,
            ],
            [
                'key' => 'field_cta_text_color',
                'label' => 'Couleur du texte',
                'name' => 'text_color',
                'type' => 'color_picker',
                'default_value' => '#ffffff',
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
