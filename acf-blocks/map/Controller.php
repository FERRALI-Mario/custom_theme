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
        return 'Intègre une carte Google Maps pour indiquer ton emplacement ou plusieurs points géographiques.';
    }

    public function getCategory(): string
    {
        return 'contact';
    }

    public function getKeywords(): array
    {
        return ['map', 'carte', 'localisation'];
    }

    public function getIcon(): string
    {
        return 'location-alt';
    }

    protected function enqueueAssets(): void
    {
        wp_enqueue_style('leaflet-css', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css', [], '1.9.4');
        wp_enqueue_script('leaflet-js', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', [], '1.9.4', true);

        $js_path = "/assets/js/map.js";
        if (file_exists(get_template_directory() . $js_path)) {
            wp_enqueue_script(
                'block-map',
                get_template_directory_uri() . $js_path,
                ['leaflet-js'],
                null,
                true
            );
        }
    }
}
