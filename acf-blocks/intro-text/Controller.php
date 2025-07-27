<?php

namespace AcfBlocks\IntroText;

use App\Core\BlockFactory;

class Controller extends BlockFactory
{
    public function __construct()
    {
        parent::__construct('intro-text');
    }

    public function getTitle(): string
    {
        return 'Texte d’introduction';
    }

    public function getDescription(): string
    {
        return 'Bloc de texte pour introduire une section ou une page, avec options de mise en forme.';
    }

    public function getCategory(): string
    {
        return 'contenu';
    }

    public function getKeywords(): array
    {
        return ['intro', 'texte', 'introduction'];
    }

    public function getIcon(): string
    {
        return 'editor-alignleft';
    }
}
