<?php

namespace AcfBlocks\Map;

use App\Core\BlockFactory;

class Controller extends BlockFactory
{
    public function __construct()
    {
        parent::__construct('map');
    }

    public function getTitle(): string
    {
        return 'Carte interactive';
    }

    public function getDescription(): string
    {
        return 'Intègre une carte Google Maps.';
    }

    public function getCategory(): string
    {
        return 'contact';
    }

    public function getKeywords(): array
    {
        return ['map', 'carte', 'google', 'localisation'];
    }

    public function getIcon(): string
    {
        return 'location-alt';
    }

    public function render(array $block, string $content = '', bool $is_preview = false, int $post_id = 0): void
    {
        $apiKey = get_field('map_api_key', $block['id']);

        if ($apiKey) {
            wp_enqueue_script(
                'google-maps-api',
                "https://maps.googleapis.com/maps/api/js?key={$apiKey}",
                [],
                null,
                true
            );
        }
        parent::render($block);
    }

    protected function enqueueAssets(): void
    {
        $js_path = "/assets/js/map.js";
        if (file_exists(get_template_directory() . $js_path)) {
            wp_enqueue_script(
                'block-map',
                get_template_directory_uri() . $js_path,
                ['google-maps-api'],
                filemtime(get_template_directory() . $js_path),
                true
            );
        }
    }
}
