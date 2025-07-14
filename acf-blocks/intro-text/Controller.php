<?php

namespace AcfBlocks\IntroText;

use App\Core\BlockFactory;

class Controller extends BlockFactory
{
    public function __construct()
    {
        parent::__construct('intro-text'); // Enregistrement du bloc 'intro-text'
    }

    /**
     * Récupère le titre du bloc
     */
    public function getTitle(): string
    {
        return 'Intro Text'; // Nom du bloc
    }

    /**
     * Récupère la description du bloc
     */
    public function getDescription(): string
    {
        return 'Bloc pour afficher un texte d\'introduction avec titre et paragraphe.';
    }

    /**
     * Récupère les mots-clés du bloc
     */
    public function getKeywords(): array
    {
        return ['intro', 'texte', 'introduction'];
    }

    /**
     * Récupère l'icône du bloc
     */
    public function getIcon(): string
    {
        return 'editor-alignleft'; // Icône d'alignement gauche
    }
}
