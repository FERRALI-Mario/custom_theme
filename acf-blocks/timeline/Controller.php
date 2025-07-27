<?php

namespace AcfBlocks\Timeline;

use App\Core\BlockFactory;
use Timber\Timber;

class Controller extends BlockFactory
{
    public function __construct()
    {
        parent::__construct('timeline');
    }

    public function render(array $block): void
    {
        $context = Timber::context();

        $events = get_field('events');

        if (is_array($events)) :
            usort($events, fn($a, $b) => $a['year'] <=> $b['year']);
        else :
            $events = [];
        endif;

        $context['block'] = $block;

        $context['fields'] = [
            'events' => $events,
            'title' => get_field('title'),
            'paragraph' => get_field('paragraph'),
        ];

        $previewPath = $this->getPreviewPath();

        if ($this->isPreview($block) && $previewPath) :
            $previewUrl = get_template_directory_uri() . '/' . $previewPath;
            echo '<img src="' . esc_url($previewUrl) . '" style="width:100%;height:auto;" alt="Aperçu du bloc" />';
            return;
        endif;

        Timber::render($this->getTemplatePath(), $context);
    }


    public function getTitle(): string
    {
        return 'Frise chronologique';
    }

    public function getDescription(): string
    {
        return 'Affiche une chronologie d\’événements, d\'étapes d\’un projet ou de l\’histoire de l\’entreprise.';
    }

    public function getCategory(): string
    {
        return 'mise-en-avant';
    }

    public function getKeywords(): array
    {
        return ['timeline', 'events', 'chronology'];
    }

    public function getIcon(): string
    {
        return 'schedule';
    }
}
