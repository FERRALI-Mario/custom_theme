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
        return 'Bannière d\'accueil (Hero)';
    }

    public function getDescription(): string
    {
        return 'Un grand visuel accrocheur avec titre et sous-titre pour accueillir les visiteurs sur une page.';
    }

    public function getCategory(): string
    {
        return 'contenu';
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
