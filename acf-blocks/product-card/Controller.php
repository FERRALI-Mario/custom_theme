<?php

namespace AcfBlocks\ProductCard;

use App\Core\BlockFactory;

class Controller extends BlockFactory
{
    public function __construct()
    {
        parent::__construct('product-card');

        $fieldsFile = __DIR__ . '/fields.php';
        if (file_exists($fieldsFile)) {
            require_once $fieldsFile;
        }
    }

    public function getTitle(): string
    {
        return 'Product Card';
    }

    public function getDescription(): string
    {
        return 'Carte de produit e-commerce avec image, prix et bouton.';
    }

    public function getIcon(): string
    {
        return 'cart';
    }

    public function getKeywords(): array
    {
        return ['produit', 'ecommerce', 'shop'];
    }
}
