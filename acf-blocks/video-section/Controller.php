<?php

namespace AcfBlocks\VideoSection;

use App\Core\BlockFactory;

class Controller extends BlockFactory
{
    public function __construct()
    {
        parent::__construct('video-section');
    }

    public function getTitle(): string
    {
        return 'Section vidéo';
    }

    public function getDescription(): string
    {
        return 'Intègre une vidéo YouTube, Vimeo ou un fichier local avec titre et description.';
    }

    public function getCategory(): string
    {
        return 'contenu';
    }

    public function getKeywords(): array
    {
        return ['video', 'section', 'embed'];
    }

    public function getIcon(): string
    {
        return 'format-video';
    }
}
