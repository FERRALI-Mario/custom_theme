<?php

if (!function_exists('acf_add_local_field_group')) {
    return;
}

acf_add_local_field_group([
    'key' => 'group_cta_block',
    'title' => 'Bloc CTA',
    'fields' => [
        [
            'key' => 'field_cta_title',
            'label' => 'Titre',
            'name' => 'title',
            'type' => 'text',
            'required' => 1,
        ],
        [
            'key' => 'field_cta_text',
            'label' => 'Texte',
            'name' => 'text',
            'type' => 'textarea',
            'rows' => 3,
        ],
        [
            'key' => 'field_cta_button_text',
            'label' => 'Texte du bouton',
            'name' => 'button_text',
            'type' => 'text',
        ],
        [
            'key' => 'field_cta_button_url',
            'label' => 'URL du bouton',
            'name' => 'button_url',
            'type' => 'url',
        ],
        [
            'key' => 'field_cta_background_image',
            'label' => 'Image de fond',
            'name' => 'background_image',
            'type' => 'image',
            'return_format' => 'array',
            'preview_size' => 'medium',
            'library' => 'all',
        ],
        [
            'key' => 'field_cta_background_color',
            'label' => 'Couleur de fond (classe Tailwind)',
            'name' => 'background_color',
            'type' => 'select',
            'choices' => [
                'bg-blue-600' => 'Bleu',
                'bg-gray-900' => 'Gris foncé',
                'bg-green-600' => 'Vert',
                'bg-red-600' => 'Rouge',
            ],
            'default_value' => 'bg-blue-600',
        ],
        [
            'key' => 'field_cta_fullscreen',
            'label' => 'Plein écran',
            'name' => 'fullscreen',
            'type' => 'true_false',
            'ui' => 1,
            'default_value' => 0,
        ],
    ],
    'location' => [
        [
            [
                'param' => 'block',
                'operator' => '==',
                'value' => 'acf/cta',
            ],
        ],
    ],
]);
