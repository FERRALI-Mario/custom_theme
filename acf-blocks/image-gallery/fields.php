<?php

if (function_exists('acf_add_local_field_group')) :

    acf_add_local_field_group([
        'key' => 'image_gallery',
        'title' => 'Block galerie d\'images',
        'fields' => [
            [
                'key' => 'field_image_gallery_title',
                'label' => 'Titre',
                'name' => 'title',
                'type' => 'text',
                'required' => 0,
            ],
            [
                'key' => 'field_image_gallery_subtitle',
                'label' => 'Sous-titre',
                'name' => 'subtitle',
                'type' => 'textarea',
                'rows' => 2,
                'required' => 0,
            ],
            [
                'key' => 'field_image_gallery_images',
                'label' => 'Images de la galerie',
                'name' => 'gallery_images',
                'type' => 'repeater',
                'instructions' => 'Ajoutez les images avec un lien optionnel.',
                'min' => 1,
                'layout' => 'block',
                'sub_fields' => [
                    [
                        'key' => 'field_image_gallery_image',
                        'label' => 'Image',
                        'name' => 'image',
                        'type' => 'image',
                        'return_format' => 'array',
                        'preview_size' => 'medium',
                        'required' => 1,
                    ],
                    [
                        'key' => 'field_image_gallery_link',
                        'label' => 'Lien',
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
                ],
            ],
        ],
        'style' => 'default',
        'position' => 'acf_after_title',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'active' => true,
    ]);

endif;
