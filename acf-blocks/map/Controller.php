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
        return 'Carte interactive';
    }

    public function getDescription(): string
    {
        return 'Intègre une carte Google Maps pour indiquer ton emplacement ou plusieurs points géographiques.';
    }

    public function getCategory(): string
    {
        return 'contact';
    }

    public function getKeywords(): array
    {
        return ['map', 'carte', 'localisation'];
    }

    public function getIcon(): string
    {
        return 'location-alt';
    }
}
