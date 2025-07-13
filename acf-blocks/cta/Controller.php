<?php

namespace AcfBlocks\Cta;

use App\Core\BlockFactory;

class Controller extends BlockFactory
{
    public function __construct()
    {
        parent::__construct('cta');

        $fieldsFile = __DIR__ . '/fields.php';
        if (file_exists($fieldsFile)) {
            require_once $fieldsFile;
        }
    }

    public function getTitle(): string
    {
        return 'Call to Action';
    }

    public function getDescription(): string
    {
        return 'Bloc d’appel à l’action avec texte, bouton et arrière-plan.';
    }

    public function getIcon(): string
    {
        return 'megaphone';
    }

    public function getKeywords(): array
    {
        return ['cta', 'appel', 'bouton'];
    }
}
