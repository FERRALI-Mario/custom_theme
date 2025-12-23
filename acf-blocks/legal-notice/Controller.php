<?php

namespace AcfBlocks\LegalNotice;

use App\Core\BlockFactory;

class Controller extends BlockFactory
{
    public function __construct()
    {
        parent::__construct('legal-notice');
    }

    public function getTitle(): string
    {
        return 'Mentions légales';
    }

    public function getDescription(): string
    {
        return 'Liste des mentions légales paragraphe par paragraphe.';
    }

    public function getCategory(): string
    {
        return 'contenu';
    }

    public function getKeywords(): array
    {
        return ['mentions-légales', 'accordéon'];
    }

    public function getIcon(): string
    {
        return 'media-document';
    }
}
