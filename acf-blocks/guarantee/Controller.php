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
        return 'Affiche les engagements ou les garanties que tu offres (satisfait ou remboursé, support 24/7, etc.).';
    }

    public function getCategory(): string
    {
        return 'mise-en-avant';
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
