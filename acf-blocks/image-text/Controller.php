<?php

namespace AcfBlocks\ImageText;

use App\Core\BlockFactory;

class Controller extends BlockFactory
{
    public function __construct()
    {
        parent::__construct('image-text');
    }

    public function getTitle(): string
    {
        return 'Image et texte';
    }

    public function getDescription(): string
    {
        return 'Bloc combiné pour afficher une image avec du texte associé, parfait pour raconter une histoire ou présenter un service.';
    }

    public function getCategory(): string
    {
        return 'contenu';
    }

    public function getKeywords(): array
    {
        return ['image', 'text', 'layout'];
    }

    public function getIcon(): string
    {
        return 'format-image';
    }
}
