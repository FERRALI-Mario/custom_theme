<?php

namespace AcfBlocks\Team;

use App\Core\BlockFactory;

class Controller extends BlockFactory
{
    public function __construct()
    {
        parent::__construct('team');

        $fieldsFile = __DIR__ . '/fields.php';
        if (file_exists($fieldsFile)) {
            require_once $fieldsFile;
        }
    }

    public function getTitle(): string
    {
        return 'Équipe';
    }

    public function getDescription(): string
    {
        return 'Bloc présentant une équipe sous forme de grille responsive.';
    }

    public function getIcon(): string
    {
        return 'groups';
    }

    public function getKeywords(): array
    {
        return ['équipe', 'membres', 'staff'];
    }
}
