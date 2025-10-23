<?php
return [
    'hero'           => ['scope' => 'core'],
    'cta'            => ['scope' => 'core'],
    'intro'          => ['scope' => 'core'],
    'pricing-table'  => ['scope' => 'vitrine'],
    'contact-form'   => ['scope' => 'vitrine'],
    'post-list'      => ['scope' => 'blog'],
    'post-hero'      => ['scope' => 'blog'],
    'author-bio'     => ['scope' => 'blog'],
    'product-grid'   => ['scope' => 'ecommerce', 'requires' => ['woocommerce']],
    'product-card'   => ['scope' => 'ecommerce', 'requires' => ['woocommerce']],
    'product-filter' => ['scope' => 'ecommerce', 'requires' => ['woocommerce']],
    'cart-mini'      => ['scope' => 'ecommerce', 'requires' => ['woocommerce']],
];
