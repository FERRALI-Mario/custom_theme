<?php

if (function_exists('acf_add_local_field_group')) :

    acf_add_local_field_group([
        'key' => 'video_section',
        'title' => 'Bloc section vidéo',
        'fields' => [
            [
                'key' => 'field_video_section_title',
                'label' => 'Titre',
                'name' => 'title',
                'type' => 'text',
                'required' => 1,
            ],
            [
                'key' => 'field_video_section_subtitle',
                'label' => 'Sous-titre',
                'name' => 'subtitle',
                'type' => 'textarea',
                'required' => 0,
                'rows' => 4,
            ],
            [
                'key' => 'field_video_url',
                'label' => 'URL de la vidéo',
                'name' => 'video_url',
                'type' => 'url',
                'required' => 1,
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/video-section',
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
