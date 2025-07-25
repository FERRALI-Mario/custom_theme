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
        return 'Image and Text';
    }

    public function getDescription(): string
    {
        return 'Affiche une image et du texte avec une disposition personnalisée.';
    }

    public function getKeywords(): array
    {
        return ['image', 'text', 'layout'];
    }
}
