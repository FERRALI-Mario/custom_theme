<?php

if (function_exists('acf_add_local_field_group')) :

    acf_add_local_field_group([
        'key' => 'guarantee',
        'title' => 'Block garanties',
        'fields' => [
            [
                'key' => 'field_guarantees',
                'label' => 'Garanties',
                'name' => 'guarantees',
                'type' => 'repeater',
                'min' => 1,
                'max' => 4,
                'button_label' => 'Ajouter une garantie',
                'sub_fields' => [
                    [
                        'key' => 'field_guarantee_icon_type',
                        'label' => 'Type d’icône',
                        'name' => 'icon_type',
                        'type' => 'select',
                        'choices' => [
                            'secure-payment'   => 'Paiement sécurisé',
                            'fast-delivery'    => 'Livraison 24h',
                            'contact'          => 'Nous contacter',
                            'click-collect'    => 'Click & Collect',
                            'free-shipping'    => 'Livraison offerte',
                            'customer-support' => 'SAV réactif',
                            'warranty'         => 'Garantie 2 ans',
                            'custom'           => 'Icône personnalisée',
                        ],
                        'default_value' => 'secure-payment',
                        'return_format' => 'value',
                        'ui' => 1,
                    ],
                    [
                        'key' => 'field_guarantee_custom_icon',
                        'label' => 'Icône personnalisée (SVG ou image)',
                        'name' => 'custom_icon',
                        'type' => 'image',
                        'conditional_logic' => [
                            [
                                [
                                    'field' => 'field_guarantee_icon_type',
                                    'operator' => '==',
                                    'value' => 'custom',
                                ]
                            ]
                        ],
                        'return_format' => 'array',
                        'preview_size' => 'thumbnail',
                        'library' => 'all',
                    ],
                    [
                        'key' => 'field_guarantee_subtitle',
                        'label' => 'Sous-titre',
                        'name' => 'title',
                        'type' => 'text',
                        'required' => 1,
                    ],
                    [
                        'key' => 'field_guarantee_description',
                        'label' => 'Description',
                        'name' => 'description',
                        'type' => 'textarea',
                        'rows' => 2,
                        'required' => 1,
                    ],
                ],
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/guarantee',
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
