<?php

namespace AcfBlocks\VideoSection;

use App\Core\BlockFactory;

class Controller extends BlockFactory
{
    public function __construct()
    {
        parent::__construct('video-section');
    }

    /**
     * Récupère le titre du bloc
     */
    public function getTitle(): string
    {
        return 'Video Section';
    }

    /**
     * Récupère la description du bloc
     */
    public function getDescription(): string
    {
        return 'Bloc vidéo avec titre, description et vidéo intégrée.';
    }

    /**
     * Récupère les mots-clés du bloc
     */
    public function getKeywords(): array
    {
        return ['video', 'section', 'embed'];
    }

    /**
     * Récupère l'icône du bloc
     */
    public function getIcon(): string
    {
        return 'video';  // Icône liée à la vidéo
    }
}
