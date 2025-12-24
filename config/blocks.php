<?php

return [
    // --- BLOCS OBLIGATOIRES (Toujours installés) ---
    'core' => [
        'hero',
        'intro-text',
        'cta',
        'contact-form',
        'contact-infos',
        'image-text',
        'image-gallery',
        'legal-notice',
        'social-links',
        'quote',
        'logos-carousel',
        'number-counter',
        'faq'
    ],

    // --- PACKS OPTIONNELS (À sélectionner via la commande) ---
    'optional' => [
        'real_estate' => [
            'label' => '🏡 Immobilier / Maison',
            'blocks' => [
                'amenities',
                'features-list',
                'guarantee',
                'room-list',
                'map'
            ]
        ],
        'ecommerce' => [
            'label' => '🛒 E-Commerce (WooCommerce)',
            'blocks' => [
                'product-grid',
                'product-filter',
            ]
        ],
        'booking' => [
            'label' => '📅 Réservation / Calendrier',
            'blocks' => [
                'calendar',
            ]
        ],
        'seo' => [
            'label' => '🔍 SEO & Structure',
            'blocks' => [
                'breadcrumb'
            ]
        ]
    ]
];
