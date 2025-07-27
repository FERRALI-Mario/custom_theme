<?php

namespace AcfBlocks\FeaturesList;

use App\Core\BlockFactory;

class Controller extends BlockFactory
{
    public function __construct()
    {
        parent::__construct('features-list');
    }

    public function getTitle(): string
    {
        return 'Liste de fonctionnalités';
    }

    public function getDescription(): string
    {
        return 'Présente une liste de points forts, services ou caractéristiques de manière visuelle et structurée.';
    }

    public function getCategory(): string
    {
        return 'mise-en-avant';
    }

    public function getKeywords(): array
    {
        return ['fonctionnalités', 'avantages', 'liste'];
    }

    public function getIcon(): string
    {
        return 'media-document';
    }
}
