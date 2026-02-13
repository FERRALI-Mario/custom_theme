<?php

namespace App\Providers;

use App\Core\ServiceProvider;

class PostTypes extends ServiceProvider
{
    public function register(): void
    {
        // Idéalement, si tu as beaucoup de CPT, tu pourrais créer des fichiers séparés
        // Mais pour un seul CPT, c'est très bien ici.
        $this->registerBookingCPT();
    }

    private function registerBookingCPT(): void
    {
        register_post_type('booking', [
            'labels' => [
                'name'          => 'Réservations',
                'singular_name' => 'Réservation',
                'menu_name'     => 'Réservations',
            ],
            'public'       => false, // Privé (pas d'URL front)
            'show_ui'      => true,  // Visible admin
            'menu_icon'    => 'dashicons-calendar-alt',
            'supports'     => ['title', 'custom-fields'],
            'capabilities' => ['create_posts' => false], // Création via API/Front uniquement
            'map_meta_cap' => true,
        ]);
    }
}
