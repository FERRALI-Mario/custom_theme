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
            usort($events, fn ($a, $b) => $a['year'] <=> $b['year']);
        else :
            $events = [];
        endif;

        $context['block'] = $block;

        $context['fields'] = [
            'events' => $events,
            'title' => get_field('title'),
            'description' => get_field('description'),
        ];

        Timber::render($this->getTemplatePath(), $context);
    }


    public function getTitle(): string
    {
        return 'Timeline';
    }

    public function getDescription(): string
    {
        return 'Affiche une chronologie avec des événements et des descriptions.';
    }

    public function getKeywords(): array
    {
        return ['timeline', 'events', 'chronology'];
    }
}
