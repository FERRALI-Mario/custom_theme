<?php
if (function_exists('acf_add_local_field_group')) :
    acf_add_local_field_group([
        'key' => 'group_booking_request_calendar_fields',
        'title' => 'Demande de réservation',
        'fields' => [
            [
                'key' => 'field_brc_title',
                'label' => 'Titre',
                'name' => 'title',
                'type' => 'text',
                'required' => 1,
            ],
            [
                'key' => 'field_brc_subtitle',
                'label' => 'Sous-titre',
                'name' => 'subtitle',
                'type' => 'textarea',
                'rows' => 2,
                'required' => 1,
            ],
            // --- NOUVEAU CHAMP ICI ---
            [
                'key' => 'field_brc_min_stay',
                'label' => 'Séjour minimum (nuits)',
                'name' => 'min_stay',
                'type' => 'number',
                'default_value' => 7,
                'min' => 1,
            ],
            // --------------------------
            [
                'key' => 'field_brc_ical_url',
                'label' => 'URL iCal',
                'name' => 'ical_url',
                'type' => 'url',
            ],
            [
                'key' => 'field_brc_cache_minutes',
                'label' => 'Cache (min)',
                'name' => 'cache_minutes',
                'type' => 'number',
                'default_value' => 60,
            ],
            [
                'key' => 'field_brc_fallback',
                'label' => 'Dates bloquées (manuelles)',
                'name' => 'fallback',
                'type' => 'repeater',
                'layout' => 'row',
                'button_label' => 'Ajouter une période',
                'sub_fields' => [
                    ['key' => 'field_brc_fb_start', 'label' => 'Début', 'name' => 'fb_start', 'type' => 'date_picker', 'return_format' => 'Y-m-d', 'required' => 1],
                    ['key' => 'field_brc_fb_end', 'label' => 'Fin', 'name' => 'fb_end', 'type' => 'date_picker', 'return_format' => 'Y-m-d', 'required' => 1],
                    ['key' => 'field_brc_fb_note', 'label' => 'Note', 'name' => 'fb_note', 'type' => 'text'],
                ],
            ],
            [
                'key' => 'field_brc_success_text',
                'label' => 'Confirmation',
                'name' => 'success_text',
                'type' => 'text',
                'default_value' => 'Demande envoyée !',
            ],
            [
                'key' => 'field_brc_button_label',
                'label' => 'Bouton',
                'name' => 'button_label',
                'type' => 'text',
                'default_value' => 'Réserver',
            ],
        ],
        'location' => [[['param' => 'block', 'operator' => '==', 'value' => 'acf/booking-request-calendar']]],
    ]);
endif;
