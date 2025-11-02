<?php

namespace AcfBlocks\RoomList;

use App\Core\BlockFactory;

class Controller extends BlockFactory
{
    public function __construct()
    {
        parent::__construct('room-list');
    }

    public function getTitle(): string
    {
        return 'Liste des pièces / espaces';
    }

    public function getDescription(): string
    {
        return 'Présentation des pièces (chambres, salon, cuisine) ou espaces extérieurs.';
    }

    public function getCategory(): string
    {
        return 'maison';
    }

    public function getKeywords(): array
    {
        return ['pièces', 'espaces', 'chambres', 'salon', 'cuisine', 'extérieur', 'atouts'];
    }

    public function getIcon(): string
    {
        return 'index-card';
    }
}
