<?php

if (function_exists('acf_add_local_field_group')) :

    acf_add_local_field_group([
        'key' => 'image_text',
        'title' => 'Block image et texte',
        'fields' => [
            [
                'key' => 'field_image_text_title',
                'label' => 'Titre',
                'name' => 'title',
                'type' => 'text',
                'required' => 1,
            ],
            [
                'key' => 'field_image_text_paragraph',
                'label' => 'Paragraphe',
                'name' => 'paragraph',
                'type' => 'textarea',
                'required' => 1,
            ],
            [
                'key' => 'field_image_text_image',
                'label' => 'Image',
                'name' => 'image',
                'type' => 'image',
                'return_format' => 'url',
                'required' => 1,
            ],
            [
                'key' => 'field_image_text_button_text',
                'label' => 'Texte du bouton',
                'name' => 'button_text',
                'type' => 'text',
                'required' => 0,
            ],
            [
                'key' => 'field_image_text_button_url',
                'label' => 'URL du bouton',
                'name' => 'button_url',
                'type' => 'url',
                'required' => 0,
            ],
            [
                'key' => 'field_image_text_image_position',
                'label' => 'Position de l\image',
                'name' => 'image_position',
                'type' => 'select',
                'choices' => [
                    'left' => 'Gauche',
                    'right' => 'Droite',
                ],
                'default_value' => 'left',
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/image-text',
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
