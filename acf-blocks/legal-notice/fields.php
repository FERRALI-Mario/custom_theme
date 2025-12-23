<?php
if (function_exists('acf_add_local_field_group')) :
    acf_add_local_field_group([
        'key' => 'group_block_legal_notice',
        'title' => 'Block mentions légales',
        'fields' => [
            [
                'key' => 'field_legal_sections',
                'label' => 'Mentions légales',
                'name' => 'sections',
                'type' => 'repeater',
                'instructions' => 'Ajoutez une ou plusieurs sections.',
                'layout' => 'block',
                'button_label' => 'Ajouter une section',
                'sub_fields' => [
                    [
                        'key' => 'field_legal_section_title',
                        'label' => 'Titre',
                        'name' => 'title',
                        'type' => 'text',
                        'required' => 1,
                    ],
                    [
                        'key' => 'field_legal_section_content',
                        'label' => 'Paragraphe',
                        'name' => 'content',
                        'type' => 'textarea',
                        'tabs' => 'visual',
                        'toolbar' => 'basic',
                        'media_upload' => 0,
                        'required' => 1,
                    ],
                ],
            ],
        ],
        'location' => [[['param' => 'block', 'operator' => '==', 'value' => 'acf/legal-notice']]],
    ]);
endif;
