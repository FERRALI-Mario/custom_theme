<?php

namespace AcfBlocks\Hero;

use App\Core\BlockFactory;

class Controller extends BlockFactory
{
    public function __construct()
    {
        parent::__construct('hero');
    }

    public function getTitle(): string
    {
        return 'Hero';
    }

    public function getDescription(): string
    {
        return 'Bloc Hero avec image de fond, titre et sous-titre';
    }

    public function getKeywords(): array
    {
        return ['hero', 'bannière', 'accueil'];
    }

    public function getIcon(): string
    {
        return 'cover-image';
    }
}
