<?php

namespace AcfBlocks\LogosCarousel;

use App\Core\BlockFactory;

class Controller extends BlockFactory
{
    public function __construct()
    {
        parent::__construct('logos-carousel');

        $fieldsFile = __DIR__ . '/fields.php';
        if (file_exists($fieldsFile)) {
            require_once $fieldsFile;
        }
    }

    public function getTitle(): string
    {
        return 'Logos – Carousel';
    }

    public function getDescription(): string
    {
        return 'Affiche une liste de logos défilants en boucle.';
    }

    public function getIcon(): string
    {
        return 'images-alt2';
    }

    public function getKeywords(): array
    {
        return ['logos', 'carousel', 'partenaires'];
    }
}
