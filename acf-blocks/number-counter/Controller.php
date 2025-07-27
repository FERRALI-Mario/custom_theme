<?php

namespace AcfBlocks\NumberCounter;

use App\Core\BlockFactory;

class Controller extends BlockFactory
{
    public function __construct()
    {
        parent::__construct('number-counter');
    }

    public function getTitle(): string
    {
        return 'Compteur de chiffres';
    }

    public function getDescription(): string
    {
        return 'Affiche des statistiques dynamiques comme le nombre de clients, projets ou années d’expérience.';
    }

    public function getCategory(): string
    {
        return 'mise-en-avant';
    }

    public function getKeywords(): array
    {
        return ['counter', 'number', 'statistic', 'animated'];
    }

    public function getIcon(): string
    {
        return 'chart-bar';
    }
}
