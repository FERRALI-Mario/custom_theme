<?php

namespace App\Helpers; // ou App\Support pour uniformiser

class ProductHelpers
{
    /**
     * Retourne les choix de filtres pour WooCommerce.
     * Static car pas d'état interne.
     */
    public static function getFilterChoices(): array
    {
        return [
            'product_cat'   => 'Catégorie',
            'price'         => 'Prix',
            'on_sale'       => 'Promo',
            'stock_status'  => 'Stock',
            'product_tag'   => 'Étiquette',
            'rating'        => 'Note',
        ];
    }
}
