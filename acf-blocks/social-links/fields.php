<?php

if (function_exists('acf_add_local_field_group')) :

acf_add_local_field_group([
    'key' => 'group_social_links',
    'title' => 'Social Links',
    'fields' => [
        [
            'key' => 'field_social_links',
            'label' => 'Social Links',
            'name' => 'social_links',
            'type' => 'repeater',
            'button_label' => 'Add Social Link',
            'sub_fields' => [
                [
                    'key' => 'field_social_icon',
                    'label' => 'Icon',
                    'name' => 'social_icon',
                    'type' => 'select',
                    'choices' => [
                        'facebook'  => 'Facebook',
                        'instagram' => 'Instagram',
                        'snapchat'  => 'Snapchat',
                        'tiktok'    => 'TikTok',
                        'pinterest' => 'Pinterest',
                        'github'    => 'GitHub',
                        'linkedin'  => 'LinkedIn',
                    ],
                    'default_value' => 'facebook',
                    'instructions' => 'Select the social media icon to display.',
                    'required' => 1,
                ],
                [
                    'key' => 'field_social_url',
                    'label' => 'URL',
                    'name' => 'social_url',
                    'type' => 'url',
                    'required' => 1,
                ],
            ],
            'min' => 1,
            'max' => 7,
        ],
    ],
    'location' => [
        [
            [
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/social-links',
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
