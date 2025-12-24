<?php

namespace AcfBlocks\ContactForm;

class AjaxHandler
{
    public static function register(): void
    {
        $handler = new self();
        add_action('wp_ajax_contact_form_submit', [$handler, 'handleSubmission']);
        add_action('wp_ajax_nopriv_contact_form_submit', [$handler, 'handleSubmission']);
    }

    public function handleSubmission()
    {
        // 1. Sécurité
        if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'contact_form_action')) {
            wp_send_json_error(['message' => 'Erreur de sécurité. Rechargez la page.']);
        }

        $messageBody = "Nouveau message depuis le site web :\n\n";
        $replyTo = '';
        $recipient = sanitize_email($_POST['recipient'] ?? get_option('admin_email'));

        // Compteur de champs remplis
        $filledFields = 0;

        foreach ($_POST as $key => $value) {
            if (in_array($key, ['action', '_wpnonce', 'recipient'])) continue;

            $cleanValue = sanitize_textarea_field($value);

            // Si le champ n'est pas vide, on incrémente
            if (!empty(trim($cleanValue))) {
                $filledFields++;
            }

            $cleanKey = preg_replace('/^field_\d+_/', '', $key);
            $cleanKey = str_replace('-', ' ', $cleanKey);
            $cleanKey = ucfirst($cleanKey); // Ex: "Votre nom"

            if (is_email($cleanValue) && empty($replyTo)) {
                $replyTo = $cleanValue;
            }

            $messageBody .= "$cleanKey : \n$cleanValue\n\n";
        }

        // 2. VÉRIFICATION : Si aucun champ n'est rempli, on arrête tout
        if ($filledFields === 0) {
            wp_send_json_error(['message' => 'Veuillez remplir au moins un champ.']);
        }

        // 3. Envoi
        $headers = ['Content-Type: text/plain; charset=UTF-8'];
        $headers[] = 'From: Site Web <' . get_option('admin_email') . '>';
        if ($replyTo) $headers[] = 'Reply-To: ' . $replyTo;

        if (wp_mail($recipient, 'Nouveau contact', $messageBody, $headers)) {
            wp_send_json_success(['message' => 'Message envoyé avec succès !']);
        } else {
            wp_send_json_error(['message' => "Erreur technique lors de l'envoi."]);
        }
    }
}
