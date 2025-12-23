<?php

namespace AcfBlocks\Calendar;

use App\Core\BlockFactory;
use Timber\Timber;

class Controller extends BlockFactory
{
    public function __construct()
    {
        parent::__construct('calendar');
    }

    public function getTitle(): string
    {
        return 'Demande de réservation';
    }
    public function getDescription(): string
    {
        return 'Calendrier en lecture seule (iCal) avec sélection de dates et envoi d’une demande de réservation.';
    }
    public function getKeywords(): array
    {
        return ['calendrier', 'disponibilités', 'réservation', 'iCal', 'demande'];
    }
    public function getIcon(): string
    {
        return 'calendar-alt';
    }

    public function render(array $block, string $content = '', bool $is_preview = false, int $post_id = 0): void
    {
        $context = Timber::context();
        $fields  = function_exists('get_fields') ? (get_fields() ?: []) : [];

        // Params
        $monthsToShow = 1;
        $weekStart    = 1;
        $minStay      = (int)($fields['min_stay'] ?? 7);
        $cacheMin     = max(5, (int)($fields['cache_minutes'] ?? 60));

        // Instantiation pour utiliser les méthodes helpers (getBlockedDates)
        $handler = new AjaxHandler();

        $blocked = $handler->getBlockedDates($fields['ical_url'] ?? '', $cacheMin);
        $fb = isset($fields['fallback']) && is_array($fields['fallback']) ? $fields['fallback'] : [];
        $blocked = $handler->mergeFallback($blocked, $fb);

        $today  = new \DateTime('today', wp_timezone());
        $months = $this->buildMonths($today, $monthsToShow, $weekStart, $blocked);

        $rules = ['min_stay' => $minStay];

        $handle = 'calendar-js';
        $src    = get_template_directory_uri() . '/assets/js/calendar.js';

        if (file_exists(get_template_directory() . '/assets/js/calendar.js')) {
            wp_enqueue_script($handle, $src, [], filemtime(get_template_directory() . '/assets/js/calendar.js'), true);

            wp_localize_script($handle, 'BRC', [
                'ajaxurl'   => admin_url('admin-ajax.php'),
                'blocked'   => array_values($blocked),
                'rules'     => $rules,
                'weekStart' => $weekStart,
                'current'   => ['year' => (int)$today->format('Y'), 'month' => (int)$today->format('n')],
            ]);

            wp_localize_script($handle, 'BRC_CONTEXT', [
                'defaultPrice' => (float)($fields['price_per_night'] ?? 0),
                'seasonal'     => $fields['seasonal_prices'] ?? [],
            ]);
        }

        $context['fields']   = $fields;
        $context['block']    = $block;
        $context['months']   = $months;
        $context['weekdays'] = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];
        $context['rules']    = $rules;

        $previewPath = $this->getPreviewPath();

        if ($this->isPreview($block) && $previewPath) :
            $previewUrl = get_template_directory_uri() . '/' . $previewPath;
            echo '<img src="' . esc_url($previewUrl) . '" style="width:100%;height:auto;" alt="Aperçu du bloc" />';
            return;
        endif;

        Timber::render($this->getTemplatePath(), $context);
    }

    private function buildMonths(\DateTime $from, int $count, int $weekStart, array $blockedSet): array
    {
        $months = [];
        $blocked = array_flip($blockedSet);
        for ($i = 0; $i < $count; $i++) {
            $firstOfMonth = (clone $from)->modify("first day of +$i month");
            $monthNum = (int)$firstOfMonth->format('n');
            $yearNum  = (int)$firstOfMonth->format('Y');
            $firstDow = (int)$firstOfMonth->format('w');
            $startOffset = $weekStart === 1 ? (($firstDow + 6) % 7) : $firstDow;
            $gridStart = (clone $firstOfMonth)->modify("-$startOffset day");
            $days = [];
            for ($d = 0; $d < 42; $d++) {
                $cur = (clone $gridStart)->modify("+$d day");
                $date = $cur->format('Y-m-d');
                $inMonth = ((int)$cur->format('n') === $monthNum);
                $days[] = [
                    'date'     => $date,
                    'in_month' => $inMonth,
                    'blocked'  => isset($blocked[$date]),
                ];
            }
            $months[] = [
                'year'  => $yearNum,
                'month' => $monthNum,
                'days'  => $days,
                'label' => ucfirst(date_i18n('F', mktime(0, 0, 0, $monthNum, 10))) . ' ' . $yearNum,
            ];
        }
        return $months;
    }
}
