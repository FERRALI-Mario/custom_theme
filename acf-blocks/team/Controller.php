<?php

namespace AcfBlocks\Team;

use App\Core\BlockFactory;

class Controller extends BlockFactory
{
    public function __construct()
    {
        parent::__construct('team');
    }

    public function getTitle(): string
    {
        return 'Équipe';
    }

    public function getDescription(): string
    {
        return 'Présente les membres de ton équipe avec photo, nom, poste et description.';
    }

    public function getCategory(): string
    {
        return 'relations';
    }

    public function getKeywords(): array
    {
        return ['team', 'members', 'employees'];
    }

    public function getIcon(): string
    {
        return 'groups';
    }
}
