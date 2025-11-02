<?php

namespace AcfBlocks\Amenities;

use App\Core\BlockFactory;

class Controller extends BlockFactory
{
    public function __construct()
    {
        parent::__construct('amenities');
    }

    public function getTitle(): string
    {
        return 'Équipements';
    }

    public function getDescription(): string
    {
        return 'Équipements de la maison.';
    }

    public function getKeywords(): array
    {
        return ['amenities', 'équipements', 'catégories', 'maison d’hôtes'];
    }

    public function getIcon(): string
    {
        return 'screenoptions';
    }
}
