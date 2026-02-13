<?php

namespace AcfBlocks\ContactForm;

class AjaxHandler
{
    public static function register(): void
    {
        $handler = new self();
        add_action('wp_ajax_contact_form_submit', [$handler, 'handle']);
        add_action('wp_ajax_nopriv_contact_form_submit', [$handler, 'handle']);
    }

    public function handle(): void
    {
        if (!isset($_POST['_token']) || !wp_verify_nonce($_POST['_token'], 'contact_form_action')) {
            wp_send_json_error(['message' => 'Session expirée, veuillez recharger la page.']);
        }

        $name    = sanitize_text_field($_POST['name'] ?? '');
        $email   = sanitize_email($_POST['email'] ?? '');
        $subject = sanitize_text_field($_POST['subject'] ?? 'Nouveau contact depuis le site');
        $message = sanitize_textarea_field($_POST['message'] ?? '');

        if (empty($name) || empty($email) || empty($message)) {
            wp_send_json_error(['message' => 'Veuillez remplir tous les champs obligatoires.']);
        }

        if (!is_email($email)) {
            wp_send_json_error(['message' => 'L’adresse e-mail n’est pas valide.']);
        }

        $admin_email = get_option('admin_email');
        $headers     = [
            'Content-Type: text/html; charset=UTF-8',
            "Reply-To: $name <$email>"
        ];

        $body = "
            <h3>Nouveau message de contact</h3>
            <p><strong>Nom :</strong> $name</p>
            <p><strong>Email :</strong> $email</p>
            <p><strong>Sujet :</strong> $subject</p>
            <hr>
            <p><strong>Message :</strong><br>" . nl2br($message) . "</p>
        ";

        if (wp_mail($admin_email, "Contact : $subject", $body, $headers)) {
            wp_send_json_success(['message' => 'Merci ! Votre message a bien été envoyé.']);
        } else {
            wp_send_json_error(['message' => 'Une erreur technique est survenue lors de l’envoi.']);
        }
    }
}
