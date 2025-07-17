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
        return 'FAQ';
    }

    public function getDescription(): string
    {
        return 'Bloc FAQ avec accordéon pour afficher des questions et réponses';
    }

    public function getKeywords(): array
    {
        return ['faq', 'accordéon', 'questions'];
    }
}
