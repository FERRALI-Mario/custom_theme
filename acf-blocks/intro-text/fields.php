<?php

if (function_exists('acf_add_local_field_group')) :

    acf_add_local_field_group([
        'key' => 'group_intro_text',
        'title' => 'Intro Text Block',
        'fields' => [
            [
                'key' => 'field_intro_text_title',
                'label' => 'Titre',
                'name' => 'title',
                'type' => 'text',
                'required' => 1,
            ],
            [
                'key' => 'field_intro_text_paragraph',
                'label' => 'Paragraphe',
                'name' => 'paragraph',
                'type' => 'textarea',
                'rows' => 4,
                'required' => 1,
            ],
            [
                'key' => 'field_intro_text_alignment',
                'label' => 'Alignement du texte',
                'name' => 'alignment',
                'type' => 'select',
                'choices' => [
                    'left' => 'Gauche',
                    'center' => 'Centre',
                    'right' => 'Droite',
                ],
                'default_value' => 'left',
                'required' => 1,
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/intro-text',
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
