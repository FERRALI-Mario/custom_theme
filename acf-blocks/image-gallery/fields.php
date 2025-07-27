<?php

if (function_exists('acf_add_local_field_group')) :

    acf_add_local_field_group([
        'key' => 'group_image_gallery',
        'title' => 'Image Gallery Block',
        'fields' => [
            [
                'key' => 'field_gallery_title',
                'label' => 'Titre',
                'name' => 'title',
                'type' => 'text',
                'required' => 0,
            ],
            [
                'key' => 'field_gallery_description',
                'label' => 'Description',
                'name' => 'paragraph',
                'type' => 'textarea',
                'rows' => 2,
                'required' => 0,
            ],
            [
                'key' => 'field_gallery_images',
                'label' => 'Gallery Images',
                'name' => 'gallery_images',
                'type' => 'repeater',
                'instructions' => 'Ajoutez les images avec un lien optionnel.',
                'min' => 1,
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_gallery_image',
                        'label' => 'Image',
                        'name' => 'image',
                        'type' => 'image',
                        'return_format' => 'array',
                        'preview_size' => 'medium',
                        'required' => 1,
                    ],
                    [
                        'key' => 'field_gallery_link',
                        'label' => 'Lien (optionnel)',
                        'name' => 'link',
                        'type' => 'url',
                        'required' => 0,
                    ],
                ],
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/image-gallery',
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
