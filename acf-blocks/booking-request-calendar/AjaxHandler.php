<?php

namespace AcfBlocks\BookingRequestCalendar;

/**
 * Gère la réception du formulaire et l'envoi d'email via AJAX.
 * Ce fichier est chargé globalement par functions.php.
 */
class AjaxHandler
{
    public function __construct()
    {
        // On écoute les requêtes AJAX (Connecté et Non-connecté)
        add_action('wp_ajax_booking_request_submit', [$this, 'handleRequest']);
        add_action('wp_ajax_nopriv_booking_request_submit', [$this, 'handleRequest']);
    }

    public function handleRequest()
    {
        // 1. Sécurité (Nonce)
        if (!isset($_POST['_brc_nonce']) || !wp_verify_nonce($_POST['_brc_nonce'], 'brc_request')) {
            wp_send_json_error(['message' => 'Session expirée. Veuillez recharger la page.']);
        }

        // 2. Nettoyage des entrées
        $name     = sanitize_text_field($_POST['name'] ?? '');
        $email    = sanitize_email($_POST['email'] ?? '');
        $phone    = sanitize_text_field($_POST['phone'] ?? '');
        $message  = sanitize_textarea_field($_POST['message'] ?? '');
        $checkin  = sanitize_text_field($_POST['checkin'] ?? '');
        $checkout = sanitize_text_field($_POST['checkout'] ?? '');

        // 3. Validation
        if (!$name || !$phone || !$checkin || !$checkout) {
            wp_send_json_error(['message' => 'Veuillez remplir tous les champs obligatoires.']);
        }

        $tz = wp_timezone();
        $cin  = \DateTime::createFromFormat('Y-m-d', $checkin, $tz);
        $cout = \DateTime::createFromFormat('Y-m-d', $checkout, $tz);

        if (!$cin || !$cout || $cin >= $cout) {
            wp_send_json_error(['message' => 'Dates invalides.']);
        }

        // Sécurité Backend (Min 2 nuits pour éviter les bugs, la règle 7 nuits est en UX JS)
        $nights = (int)$cin->diff($cout)->days;
        if ($nights < 2) {
            wp_send_json_error(['message' => 'Séjour trop court.']);
        }

        // 4. Vérification Disponibilités
        $blocked = [];
        $ical = isset($_POST['ical']) ? esc_url_raw($_POST['ical']) : '';
        $cacheMin = (int)($_POST['cache'] ?? 60);

        if ($ical) {
            $blocked = $this->getBlockedDates($ical, $cacheMin);
        }

        // Fallback dates manuelles
        $fb = [];
        if (!empty($_POST['fallback_json'])) {
            $decoded = json_decode(stripslashes($_POST['fallback_json']), true);
            if (is_array($decoded)) $fb = $decoded;
        }
        $blocked = $this->mergeFallback($blocked, $fb);

        $set  = array_flip($blocked);
        $iter = clone $cin;

        while ($iter < $cout) {
            if (isset($set[$iter->format('Y-m-d')])) {
                wp_send_json_error(['message' => 'Ces dates ne sont plus disponibles.']);
            }
            $iter->modify('+1 day');
        }

        // 5. Envoi Email
        $blogname    = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
        $admin_email = get_option('admin_email');

        $subject = 'Demande de réservation : ' . $name . ' (' . $cin->format('d/m') . '-' . $cout->format('d/m') . ')';

        $body = "Nouvelle demande de réservation :\n\n" .
            "Dates   : " . $cin->format('d/m/Y') . " au " . $cout->format('d/m/Y') . "\n" .
            "Durée   : " . $nights . " nuits\n" .
            "Nom     : $name\n" .
            ($email ? "Email   : $email\n" : '') .
            "Tél     : $phone\n\n" .
            "Message :\n" . ($message ?: 'Aucun') . "\n";

        $headers = [
            'Content-Type: text/plain; charset=UTF-8',
            "From: {$blogname} <{$admin_email}>",
        ];
        if ($email) {
            $headers[] = "Reply-To: $name <$email>";
        }

        $sent = wp_mail($admin_email, $subject, $body, $headers);

        if ($sent) {
            wp_send_json_success(['message' => 'Votre demande a bien été envoyée !']);
        } else {
            error_log('BRC Mail Error: ' . print_r(error_get_last(), true));
            wp_send_json_error(['message' => "Erreur technique lors de l'envoi."]);
        }
    }

    /** --- HELPERS (Privés pour l'usage interne) --- */

    public function getBlockedDates($url, $cacheMinutes)
    {
        $set = [];
        if (!$url) return $set;
        $key = 'brc_ics_' . md5($url);
        $cached = get_transient($key);
        if ($cached && is_array($cached)) return $cached;

        $resp = wp_remote_get($url, ['timeout' => 10]);
        if (!is_wp_error($resp) && wp_remote_retrieve_response_code($resp) === 200) {
            $body = (string) wp_remote_retrieve_body($resp);
            $set  = $this->parseIcs($body);
            set_transient($key, $set, MINUTE_IN_SECONDS * $cacheMinutes);
        }
        return $set;
    }

    private function parseIcs($ics)
    {
        $tz = wp_timezone();
        $lines = preg_split("/\r\n|\n|\r/", $ics);
        $unfold = [];
        foreach ($lines as $ln) {
            if (isset($unfold[count($unfold) - 1]) && strlen($ln) && ($ln[0] === ' ' || $ln[0] === "\t")) {
                $unfold[count($unfold) - 1] .= substr($ln, 1);
            } else {
                $unfold[] = $ln;
            }
        }
        $blocked = [];
        $dtstart = null;
        $dtend = null;
        foreach ($unfold as $ln) {
            if (str_starts_with($ln, 'BEGIN:VEVENT')) {
                $dtstart = $dtend = null;
            } elseif (str_starts_with($ln, 'DTSTART')) {
                $dtstart = $this->parseDate($ln, $tz);
            } elseif (str_starts_with($ln, 'DTEND')) {
                $dtend = $this->parseDate($ln, $tz);
            } elseif (str_starts_with($ln, 'END:VEVENT')) {
                if ($dtstart) {
                    if (!$dtend) {
                        $dtend = clone $dtstart;
                        $dtend->modify('+1 day');
                    }
                    $iter = clone $dtstart;
                    while ($iter < $dtend) {
                        $blocked[] = $iter->format('Y-m-d');
                        $iter->modify('+1 day');
                    }
                }
                $dtstart = $dtend = null;
            }
        }
        return array_values(array_unique($blocked));
    }

    private function parseDate($line, $tz)
    {
        if (!str_contains($line, ':')) return null;
        [$k, $v] = explode(':', $line, 2);
        $v = trim($v);
        if (str_contains($k, 'VALUE=DATE')) return \DateTime::createFromFormat('Ymd', $v, $tz) ?: null;
        if (str_ends_with($v, 'Z')) {
            $dt = \DateTime::createFromFormat('Ymd\THis\Z', $v, new \DateTimeZone('UTC'));
            if ($dt) $dt->setTimezone($tz);
            return $dt ?: null;
        }
        return \DateTime::createFromFormat('Ymd\THis', $v, $tz) ?: null;
    }

    public function mergeFallback($blocked, $fallback)
    {
        $tz = wp_timezone();
        foreach ($fallback as $row) {
            $s = isset($row['fb_start']) ? \DateTime::createFromFormat('Y-m-d', $row['fb_start'], $tz) : null;
            $e = isset($row['fb_end']) ? \DateTime::createFromFormat('Y-m-d', $row['fb_end'], $tz) : null;
            if (!$s) continue;
            if (!$e) $e = clone $s;
            $iter = clone $s;
            while ($iter <= $e) {
                $blocked[] = $iter->format('Y-m-d');
                $iter->modify('+1 day');
            }
        }
        return array_values(array_unique($blocked));
    }
}
