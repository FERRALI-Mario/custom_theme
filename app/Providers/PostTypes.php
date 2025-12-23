<?php

namespace App\Providers;

class PostTypes
{
    public static function register(): void
    {
        register_post_type('booking', [
            'labels' => [
                'name'          => 'Réservations',
                'singular_name' => 'Réservation',
                'menu_name'     => 'Réservations',
            ],
            'public'       => false, // Privé
            'show_ui'      => true,  // Visible admin
            'menu_icon'    => 'dashicons-calendar-alt',
            'supports'     => ['title', 'custom-fields'],
            'capabilities' => ['create_posts' => false], // Création auto uniquement
            'map_meta_cap' => true,
        ]);
    }
}
