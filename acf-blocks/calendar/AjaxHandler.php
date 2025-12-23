<?php

namespace AcfBlocks\Calendar;

/**
 * Gère la logique métier (Disponibilités, Prix) et les requêtes AJAX.
 */
class AjaxHandler
{
    /**
     * Enregistre les hooks AJAX.
     * À appeler UNE SEULE FOIS dans Theme::init().
     */
    public static function register(): void
    {
        $handler = new self();
        add_action('wp_ajax_booking_request_submit', [$handler, 'handleRequest']);
        add_action('wp_ajax_nopriv_booking_request_submit', [$handler, 'handleRequest']);
    }

    public function handleRequest()
    {
        if (!isset($_POST['_brc_nonce']) || !wp_verify_nonce($_POST['_brc_nonce'], 'brc_request')) {
            wp_send_json_error(['message' => 'Session expirée. Rechargez la page.']);
        }

        $name     = sanitize_text_field($_POST['name'] ?? '');
        $email    = sanitize_email($_POST['email'] ?? '');
        $phone    = sanitize_text_field($_POST['phone'] ?? '');
        $message  = sanitize_textarea_field($_POST['message'] ?? '');
        $checkin  = sanitize_text_field($_POST['checkin'] ?? '');
        $checkout = sanitize_text_field($_POST['checkout'] ?? '');

        if (!$name || !$email || !$phone || !$checkin || !$checkout) {
            wp_send_json_error(['message' => 'Tous les champs obligatoires doivent être remplis.']);
        }

        $tz = wp_timezone();
        $cin  = \DateTime::createFromFormat('Y-m-d', $checkin, $tz);
        $cout = \DateTime::createFromFormat('Y-m-d', $checkout, $tz);

        if (!$cin || !$cout || $cin >= $cout) {
            wp_send_json_error(['message' => 'Les dates sélectionnées sont invalides.']);
        }

        $nights = (int)$cin->diff($cout)->days;
        if ($nights < 2) {
            wp_send_json_error(['message' => 'Le séjour est trop court (min 2 nuits).']);
        }

        $icalUrl = isset($_POST['ical']) ? esc_url_raw($_POST['ical']) : '';
        $cacheMin = (int)($_POST['cache'] ?? 60);

        $blocked = [];
        if ($icalUrl) {
            $blocked = $this->getBlockedDates($icalUrl, $cacheMin);
        }

        $fb = [];
        if (!empty($_POST['fallback_json'])) {
            $decoded = json_decode(stripslashes($_POST['fallback_json']), true);
            if (is_array($decoded)) $fb = $decoded;
        }
        $blocked = $this->mergeFallback($blocked, $fb);
        $blockedSet = array_flip($blocked);

        $iter = clone $cin;
        while ($iter < $cout) {
            if (isset($blockedSet[$iter->format('Y-m-d')])) {
                wp_send_json_error(['message' => 'Désolé, ces dates ne sont plus disponibles.']);
            }
            $iter->modify('+1 day');
        }

        $rules = json_decode(stripslashes($_POST['pricing_rules'] ?? '{}'), true);
        $defaultPrice = isset($rules['default']) ? (float)$rules['default'] : 0;
        $seasons      = isset($rules['seasonal']) ? $rules['seasonal'] : [];

        $totalPrice = 0;
        $iter = clone $cin;

        while ($iter < $cout) {
            $currentMD = $iter->format('m-d');
            $nightPrice = 0;
            $seasonFound = false;

            if (!empty($seasons) && is_array($seasons)) {
                foreach ($seasons as $season) {
                    $startMD = substr($season['start_date'], 5);
                    $endMD   = substr($season['end_date'], 5);

                    $inSeason = false;
                    // Gestion hiver (cheval sur année)
                    if ($startMD > $endMD) {
                        if ($currentMD >= $startMD || $currentMD <= $endMD) $inSeason = true;
                    } else {
                        if ($currentMD >= $startMD && $currentMD <= $endMD) $inSeason = true;
                    }

                    if ($inSeason) {
                        $p = (float)$season['price'];
                        if ($p <= 0) {
                            wp_send_json_error(['message' => 'Réservation impossible sur cette période (Tarif non défini).']);
                        }
                        $nightPrice = $p;
                        $seasonFound = true;
                        break;
                    }
                }
            }

            if (!$seasonFound) {
                wp_send_json_error(['message' => "L'établissement est fermé à ces dates."]);
            }

            $totalPrice += $nightPrice;
            $iter->modify('+1 day');
        }

        $depositAmount = $totalPrice * 0.40;

        $booking_title = sprintf('%s - %s (%s)', $name, $cin->format('d/m/Y'), $nights . ' nuits');

        $booking_id = wp_insert_post([
            'post_type'   => 'booking',
            'post_title'  => $booking_title,
            'post_status' => 'publish', // Publié = visible admin (mais privé site)
        ]);

        if (!$booking_id) {
            wp_send_json_error(['message' => "Erreur technique lors de la création de la réservation."]);
        }

        update_post_meta($booking_id, '_brc_client_name', $name);
        update_post_meta($booking_id, '_brc_client_email', $email);
        update_post_meta($booking_id, '_brc_phone', $phone);
        update_post_meta($booking_id, '_brc_message', $message);
        update_post_meta($booking_id, '_brc_start_date', $cin->format('Y-m-d'));
        update_post_meta($booking_id, '_brc_end_date', $cout->format('Y-m-d'));
        update_post_meta($booking_id, '_brc_total_price', $totalPrice);
        update_post_meta($booking_id, '_brc_deposit_amount', $depositAmount);

        update_post_meta($booking_id, '_brc_payment_status', 'pending');

        $paymentLink = home_url('/paiement/') . '?booking_id=' . $booking_id;

        $this->sendEmails($name, $email, $phone, $message, $paymentLink, $cin, $cout, $nights, $totalPrice, $depositAmount);
    }

    private function sendEmails($name, $email, $phone, $message, $paymentLink, $cin, $cout, $nights, $totalPrice, $depositAmount)
    {
        $blogname    = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
        $admin_email = get_option('admin_email');

        // Mail Client (Confirmation réception)
        if ($email) {
            $headers = ['Content-Type: text/plain; charset=UTF-8', "From: \"$blogname\" <$admin_email>", "Reply-To: $admin_email"];
            $body = "Bonjour $name,\n\nNous avons bien reçu votre demande pour du " . $cin->format('d/m/Y') . " au " . $cout->format('d/m/Y') . ".\nTotal: $totalPrice €.\n\nNous reviendrons vers vous rapidement pour validation.\n\nCordialement,\n$blogname";
            wp_mail($email, "Votre demande - $blogname", $body, $headers);
        }

        // Mail Admin (Action requise)
        $admin_subject = "🔔 Demande : $name ($nights nuits)";
        $admin_body = "Nouvelle demande :\n\nClient : $name\nEmail : $email\nTél : $phone\n\nDates : " . $cin->format('d/m/Y') . " - " . $cout->format('d/m/Y') . "\nPrix Total : $totalPrice €\nAcompte (40%) : $depositAmount €\n\nMessage : " . ($message ?: 'Aucun') . "\n\n------------------\nLIEN DE PAIEMENT À ENVOYER APRÈS VALIDATION :\n$paymentLink\n------------------";
        $admin_headers = ['Content-Type: text/plain; charset=UTF-8', "From: \"Site Web\" <$admin_email>"];
        if ($email) $admin_headers[] = "Reply-To: $name <$email>";

        if (wp_mail($admin_email, $admin_subject, $admin_body, $admin_headers)) {
            wp_send_json_success(['message' => 'Votre demande a bien été envoyée !']);
        } else {
            wp_send_json_error(['message' => "Erreur technique lors de l'envoi."]);
        }
    }

    // --- Helpers Publics (utilisés par le Controller du bloc) ---

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
}
