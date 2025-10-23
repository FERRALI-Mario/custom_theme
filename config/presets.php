<?php
return [
    'vitrine' => [
        'packs'   => ['vitrine'],
        'plugins' => [
            ['slug' => 'advanced-custom-fields-pro', 'required' => true],
        ],
        'blocks'  => ['hero', 'intro', 'cta', 'pricing-table', 'contact-form'],
    ],
    'ecommerce' => [
        'packs'   => ['ecommerce', 'blog'],
        'plugins' => [
            ['slug' => 'advanced-custom-fields-pro', 'required' => true],
            ['slug' => 'woocommerce', 'required' => true],
        ],
        'blocks'  => ['product-grid', 'product-card', 'product-filter', 'cart-mini', 'hero', 'cta'],
    ],
    'blog' => [
        'packs'   => ['blog'],
        'plugins' => [
            ['slug' => 'advanced-custom-fields-pro', 'required' => true],
        ],
        'blocks'  => ['post-list', 'post-hero', 'author-bio', 'cta'],
    ],
];
