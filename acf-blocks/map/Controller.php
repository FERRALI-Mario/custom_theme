<?php

namespace AcfBlocks\Map;

use App\Core\BlockFactory;

class Controller extends BlockFactory
{
    public function __construct()
    {
        parent::__construct('map');
    }

    public function getTitle(): string
    {
        return 'Map';
    }

    public function getDescription(): string
    {
        return 'Affiche une carte interactive avec un marqueur basé sur une latitude et une longitude';
    }

    public function getKeywords(): array
    {
        return ['map', 'carte', 'localisation'];
    }
}
