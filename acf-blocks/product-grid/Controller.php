<?php

namespace AcfBlocks\ProductGrid;

use App\Core\BlockFactory;

class Controller extends BlockFactory
{
    public function __construct()
    {
        parent::__construct('product-grid');

        $fieldsFile = __DIR__ . '/fields.php';
        if (file_exists($fieldsFile)) {
            require_once $fieldsFile;
        }
    }

    public function getTitle(): string
    {
        return 'Grille de produits';
    }

    public function getDescription(): string
    {
        return 'Affiche une grille de cartes produits avec mise en page personnalisable.';
    }

    public function getIcon(): string
    {
        return 'grid-view';
    }

    public function getKeywords(): array
    {
        return ['produits', 'grille', 'ecommerce'];
    }
}
