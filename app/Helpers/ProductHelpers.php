<?php

namespace App\Helpers;

class ProductHelpers
{
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
