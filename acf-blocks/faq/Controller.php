<?php

namespace AcfBlocks\Faq;

use App\Core\BlockFactory;

class Controller extends BlockFactory
{
    public function __construct()
    {
        parent::__construct('faq');
    }

    public function getTitle(): string
    {
        return 'FAQ (Foire aux questions)';
    }

    public function getDescription(): string
    {
        return 'Liste de questions/réponses fréquentes pour rassurer ou informer tes visiteurs.';
    }

    public function getCategory(): string
    {
        return 'contact';
    }

    public function getKeywords(): array
    {
        return ['faq', 'accordéon', 'questions'];
    }

    public function getIcon(): string
    {
        return 'editor-help';
    }
}
