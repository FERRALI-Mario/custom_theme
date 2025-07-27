<?php

if (function_exists('acf_add_local_field_group')) :

    acf_add_local_field_group([
        'key' => 'group_faq',
        'title' => 'Bloc FAQ',
        'fields' => [
            [
                'key' => 'field_faq_title',
                'label' => 'Titre principal',
                'name' => 'title',
                'type' => 'text',
                'instructions' => 'Titre affiché au-dessus de la FAQ',
                'required' => 0,
                'wrapper' => ['width' => '100'],
            ],
            [
                'key' => 'field_faq_description',
                'label' => 'Description',
                'name' => 'paragraph',
                'type' => 'textarea',
                'instructions' => 'Description optionnelle affichée sous le titre',
                'rows' => 2,
                'wrapper' => ['width' => '100'],
            ],
            [
                'key' => 'field_faq_entries',
                'label' => 'Questions et Réponses',
                'name' => 'faq_entries',
                'type' => 'repeater',
                'button_label' => 'Ajouter une question',
                'min' => 1,
                'max' => 10,
                'sub_fields' => [
                    [
                        'key' => 'field_question',
                        'label' => 'Question',
                        'name' => 'question',
                        'type' => 'text',
                        'required' => 1,
                    ],
                    [
                        'key' => 'field_answer',
                        'label' => 'Réponse',
                        'name' => 'answer',
                        'type' => 'textarea',
                        'required' => 1,
                        'rows' => 3,
                    ],
                ],
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/faq',
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
