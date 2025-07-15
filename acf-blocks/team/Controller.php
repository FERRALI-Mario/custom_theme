<?php

namespace AcfBlocks\Team;

use App\Core\BlockFactory;

class Controller extends BlockFactory
{
    public function __construct()
    {
        parent::__construct('team');
    }

    /**
     * Retourne les champs ACF.
     */
    public function getFields(): array
    {
        return function_exists('get_fields') ? get_fields() ?: [] : [];
    }

    // Méthodes de base pour le bloc
    public function getTitle(): string
    {
        return 'Team';
    }

    public function getDescription(): string
    {
        return 'Affiche une grille des membres de l\'équipe.';
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
