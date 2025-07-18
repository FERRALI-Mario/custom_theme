<?php

namespace AcfBlocks\Guarantee;

use App\Core\BlockFactory;

class Controller extends BlockFactory
{
    public function __construct()
    {
        parent::__construct('guarantee');
    }

    public function getTitle(): string
    {
        return 'Garanties';
    }

    public function getDescription(): string
    {
        return 'Affiche jusqu’à 4 garanties (icône + texte) avec effet hover stylisé.';
    }

    public function getKeywords(): array
    {
        return ['garantie', 'avantage', 'confiance'];
    }

    public function getIcon(): string
    {
        return 'shield-alt';
    }
}
