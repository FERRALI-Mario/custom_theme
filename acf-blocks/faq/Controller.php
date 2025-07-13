<?php

namespace AcfBlocks\Faq;

use App\Core\BlockFactory;

class Controller extends BlockFactory
{
    public function __construct()
    {
        parent::__construct('faq');

        $fieldsFile = __DIR__ . '/fields.php';
        if (file_exists($fieldsFile)) {
            require_once $fieldsFile;
        }
    }

    public function getTitle(): string
    {
        return 'FAQ';
    }

    public function getDescription(): string
    {
        return 'Liste de questions/réponses avec accordéon.';
    }

    public function getIcon(): string
    {
        return 'editor-help';
    }

    public function getKeywords(): array
    {
        return ['faq', 'accordéon', 'questions'];
    }
}
