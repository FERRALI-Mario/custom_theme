<?php

namespace App\Paiement;

use Timber\Timber;

class PaymentController
{
    private static function getStripeKey()
    {
        $mode = get_field('stripe_mode', 'option');
        if ($mode === 'live') {
            return get_field('stripe_live_key', 'option');
        }
        return get_field('stripe_test_key', 'option');
    }

    public static function viewPayment()
    {
        $booking_id = (int) ($_GET['booking_id'] ?? 0);

        if (!$booking_id || get_post_type($booking_id) !== 'booking') {
            wp_die('Lien de réservation invalide ou expiré.');
        }

        $status = get_post_meta($booking_id, '_brc_payment_status', true);

        if ($status === 'paid') {
            $context = Timber::context();
            Timber::render('pages/paid.twig', $context);
            exit;
        }

        $infos = [
            'client'  => get_post_meta($booking_id, '_brc_client_name', true),
            'email'   => get_post_meta($booking_id, '_brc_client_email', true),
            'montant' => (float) get_post_meta($booking_id, '_brc_deposit_amount', true),
            'total'   => (float) get_post_meta($booking_id, '_brc_total_price', true),
            'start'   => date('d/m/Y', strtotime(get_post_meta($booking_id, '_brc_start_date', true))),
            'end'     => date('d/m/Y', strtotime(get_post_meta($booking_id, '_brc_end_date', true))),
            'id'      => $booking_id
        ];

        $context = Timber::context();
        $context['infos'] = $infos;

        if (isset($_POST['pay_stripe'])) {
            $apiKey = self::getStripeKey();
            if (!$apiKey) wp_die('Erreur de configuration Stripe (Clé manquante).');

            \Stripe\Stripe::setApiKey($apiKey);

            try {
                $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
                $domain = $protocol . "://$_SERVER[HTTP_HOST]";

                $session = \Stripe\Checkout\Session::create([
                    'payment_method_types' => ['card'],
                    'customer_email' => $infos['email'],
                    'line_items' => [[
                        'price_data' => [
                            'currency' => 'eur',
                            'product_data' => [
                                'name' => 'Acompte Réservation #' . $booking_id,
                                'description' => "Du " . $infos['start'] . " au " . $infos['end'],
                            ],
                            'unit_amount' => intval($infos['montant'] * 100), // En centimes
                        ],
                        'quantity' => 1,
                    ]],
                    'mode' => 'payment',
                    'metadata' => [
                        'booking_id' => $booking_id // IMPORTANT : On passe l'ID à Stripe pour le retour
                    ],
                    'success_url' => $domain . '/success?session_id={CHECKOUT_SESSION_ID}',
                    'cancel_url' => home_url($domain),
                ]);

                wp_redirect($session->url);
                exit; // Arrêt du script après redirection
            } catch (\Exception $e) {
                $context['error'] = "Erreur Stripe : " . $e->getMessage();
            }
        }

        Timber::render('pages/payment.twig', $context);
    }

    public static function viewSuccess()
    {
        if (empty($_GET['session_id'])) {
            wp_redirect(home_url());
            exit;
        }

        $apiKey = self::getStripeKey();
        \Stripe\Stripe::setApiKey($apiKey);

        try {
            $session = \Stripe\Checkout\Session::retrieve($_GET['session_id']);

            if (!empty($session->metadata->booking_id)) {
                $booking_id = (int) $session->metadata->booking_id;
                update_post_meta($booking_id, '_brc_payment_status', 'paid');
                update_post_meta($booking_id, '_brc_stripe_intent', $session->payment_intent);

                // Envoi d'un email de confirmation HTML au client
                self::sendPaymentConfirmation($booking_id);
            }

            $context = Timber::context();
            $context['customer'] = $session->metadata;
            Timber::render('pages/success.twig', $context);
        } catch (\Exception $e) {
            wp_die("Erreur lors de la vérification du paiement : " . $e->getMessage());
        }
    }

    /**
     * Envoie un email HTML de confirmation de paiement au client et à l'admin.
     *
     * @param int $booking_id
     */
    private static function sendPaymentConfirmation(int $booking_id): void
    {
        $email = get_post_meta($booking_id, '_brc_client_email', true);
        if (!$email) {
            return; // pas d'adresse email renseignée
        }

        $name   = get_post_meta($booking_id, '_brc_client_name', true);
        $phone  = get_post_meta($booking_id, '_brc_phone', true);
        $amount = (float) get_post_meta($booking_id, '_brc_deposit_amount', true);
        $total  = (float) get_post_meta($booking_id, '_brc_total_price', true);
        $start  = date('d/m/Y', strtotime(get_post_meta($booking_id, '_brc_start_date', true)));
        $end    = date('d/m/Y', strtotime(get_post_meta($booking_id, '_brc_end_date', true)));

        $blogname    = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
        $admin_email = get_option('admin_email');

        // ===== Email au CLIENT =====
        $client_subject = "Confirmation de paiement – Réservation #{$booking_id}";

        $client_body  = "<p>Bonjour " . esc_html($name) . ",</p>";
        $client_body .= "<p>Nous avons bien reçu votre paiement de <strong>" . esc_html(number_format(
            $amount,
            2,
            ',',
            ' '
        )) . " €</strong> pour la réservation <strong>" . esc_html('#' . $booking_id) . "</strong>.</p>";
        $client_body .= "<p>Votre séjour du <strong>{$start}</strong> au <strong>{$end}</strong> a été confirmé.</p>";
        $client_body .= "<p>Merci de votre confiance.</p>";
        $client_body .= "<p>Cordialement,<br>{$blogname}</p>";

        $client_headers = [
            'Content-Type: text/html; charset=UTF-8',
            "From: \"{$blogname}\" <{$admin_email}>"
        ];

        wp_mail($email, $client_subject, $client_body, $client_headers);

        // ===== Email à L'ADMIN =====
        $admin_subject = "✅ Paiement confirmé – Réservation #{$booking_id}";

        $admin_body  = "<p>Bonjour,</p>";
        $admin_body .= "<p><strong>Un paiement a été reçu !</strong></p>";
        $admin_body .= "<p><strong>Client :</strong> " . esc_html($name) . "<br>";
        $admin_body .= "<strong>Email :</strong> " . esc_html($email) . "<br>";
        $admin_body .= "<strong>Téléphone :</strong> " . esc_html($phone) . "</p>";
        $admin_body .= "<p><strong>Détails de la réservation :</strong><br>";
        $admin_body .= "Dates : " . esc_html($start) . " → " . esc_html($end) . "<br>";
        $admin_body .= "Acompte payé : " . esc_html(number_format($amount, 2, ',', ' ')) . " €<br>";
        $admin_body .= "Prix total : " . esc_html(number_format($total, 2, ',', ' ')) . " €</p>";
        $admin_body .= "<p><a href=\"" . esc_url(admin_url("post.php?post={$booking_id}&action=edit")) . "\">Voir la réservation</a></p>";

        $admin_headers = [
            'Content-Type: text/html; charset=UTF-8',
            "From: \"Site Web\" <{$admin_email}>"
        ];

        wp_mail($admin_email, $admin_subject, $admin_body, $admin_headers);
    }
}
