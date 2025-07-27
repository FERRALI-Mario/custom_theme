<?php

if (function_exists('acf_add_local_field_group')) :

    acf_add_local_field_group([
        'key' => 'group_image_text',
        'title' => 'Image and Text Block',
        'fields' => [
            [
                'key' => 'field_title',
                'label' => 'Title',
                'name' => 'title',
                'type' => 'text',
                'required' => 1,
            ],
            [
                'key' => 'field_text',
                'label' => 'Text',
                'name' => 'paragraph',
                'type' => 'textarea',
                'required' => 1,
            ],
            [
                'key' => 'field_image',
                'label' => 'Image',
                'name' => 'image',
                'type' => 'image',
                'return_format' => 'url',
                'required' => 1,
            ],
            [
                'key' => 'field_button_text',
                'label' => 'Button Text',
                'name' => 'button_text',
                'type' => 'text',
                'required' => 0,
            ],
            [
                'key' => 'field_button_url',
                'label' => 'Button URL',
                'name' => 'button_url',
                'type' => 'url',
                'required' => 0,
            ],
            [
                'key' => 'field_image_position',
                'label' => 'Image Position',
                'name' => 'image_position',
                'type' => 'select',
                'choices' => [
                    'left' => 'Left',
                    'right' => 'Right',
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
