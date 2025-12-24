<?php

namespace App\Contact;

use WP_REST_Request;
use WP_REST_Response;
use WP_Error;

class ContactController
{
    public static function registerRoutes(): void
    {
        add_action('rest_api_init', [self::class, 'registerEndpoints']);
    }

    public static function registerEndpoints(): void
    {
        register_rest_route('theme/v1', '/contact', [
            'methods' => 'POST',
            'permission_callback' => '__return_true', // Validation faite via Nonce dans le handle
            'callback' => [self::class, 'handleSubmission']
        ]);
    }

    public static function handleSubmission(WP_REST_Request $request)
    {
        $params = $request->get_json_params();
        $headers = $request->get_headers();

        $nonce = $headers['x_wp_nonce'][0] ?? '';
        if (!wp_verify_nonce($nonce, 'wp_rest')) {
            return new WP_Error('forbidden', 'Sécurité invalide (Nonce). Rechargez la page.', ['status' => 403]);
        }

        if (empty($params)) {
            return new WP_Error('no_data', 'Aucune donnée reçue', ['status' => 400]);
        }

        $message = "Nouveau message depuis le site web :\n\n";
        $replyTo = '';

        foreach ($params as $key => $value) {
            if (in_array($key, ['action', '_wpnonce', 'recipient'])) continue;

            $cleanValue = sanitize_text_field($value);
            $cleanKey = str_replace(['field_', '_'], ['Champ ', ' '], $key);

            if (is_email($cleanValue) && empty($replyTo)) {
                $replyTo = $cleanValue;
            }

            $message .= ucfirst($cleanKey) . ": " . $cleanValue . "\n";
        }

        $to = get_option('admin_email');
        if (!empty($params['recipient']) && is_email($params['recipient'])) {
            $to = sanitize_email($params['recipient']);
        }

        $emailHeaders = [];
        if ($replyTo) {
            $emailHeaders[] = 'Reply-To: ' . $replyTo;
        }

        $sent = wp_mail($to, 'Nouveau contact via le site', $message, $emailHeaders);

        if ($sent) {
            return new WP_REST_Response(['message' => 'Email envoyé avec succès', 'success' => true], 200);
        } else {
            return new WP_Error('cant_send', 'Erreur serveur lors de l\'envoi de l\'email.', ['status' => 500]);
        }
    }
}
