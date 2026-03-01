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
        return 'Calendrier iCal avec formulaire de demande de réservation.';
    }
    public function getKeywords(): array
    {
        return ['calendrier', 'disponibilités', 'réservation', 'iCal', 'demande'];
    }
    public function getIcon(): string
    {
        return 'calendar-alt';
    }

    public function getCategory(): string
    {
        return 'maison';
    }

    public function render(array $block, string $content = '', bool $is_preview = false, int $post_id = 0): void
    {
        $context = Timber::context();
        $fields  = $this->getFields(); // Utilise le helper du parent
        $handler = new AjaxHandler();

        $cacheMin = max(5, (int)($fields['cache_minutes'] ?? 60));
        $icalUrl  = $fields['ical_url'] ?? '';

        $blocked = $handler->getBlockedDates($icalUrl, $cacheMin);

        $fb = isset($fields['fallback']) && is_array($fields['fallback']) ? $fields['fallback'] : [];
        $blocked = $handler->mergeFallback($blocked, $fb);

        $monthsToShow = 1;
        $weekStart    = 1; // Lundi
        $today        = new \DateTime('today', wp_timezone());
        $months       = $this->buildMonths($today, $monthsToShow, $weekStart, $blocked);

        $rules = [
            'min_stay' => (int)($fields['min_stay'] ?? 7)
        ];

        $this->enqueueCalendarScript($blocked, $rules, $weekStart, $today, $fields);

        $context['fields']   = $fields;
        $context['block']    = $block;
        $context['months']   = $months;
        $context['weekdays'] = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];
        $context['rules']    = $rules;

        if ($this->isPreview($block) && $this->getPreviewPath()) {
            echo sprintf('<img src="%s" alt="Aperçu du bloc" style="width:100%%;height:auto;" />', esc_url(get_template_directory_uri() . '/' . $this->getPreviewPath()));
            return;
        }

        Timber::render($this->getTemplatePath(), $context);
    }

    private function enqueueCalendarScript(array $blocked, array $rules, int $weekStart, \DateTime $today, array $fields): void
    {
        $handle = 'calendar-js';
        $src    = get_template_directory_uri() . '/assets/js/calendar.js';
        $path   = get_template_directory() . '/assets/js/calendar.js';

        if (file_exists($path)) {
            wp_enqueue_script($handle, $src, [], filemtime($path), true);

            wp_localize_script($handle, 'BRC', [
                'ajaxurl'   => admin_url('admin-ajax.php'),
                'blocked'   => array_values($blocked),
                'rules'     => $rules,
                'weekStart' => $weekStart,
                'current'   => [
                    'year'  => (int)$today->format('Y'),
                    'month' => (int)$today->format('n')
                ],
            ]);

            wp_localize_script($handle, 'BRC_CONTEXT', [
                'defaultPrice'  => (float)($fields['price_per_night'] ?? 0),
                'seasonal'      => $fields['seasonal_prices'] ?? [],
                // cleaning fee per booking (fixed)
                'cleaning_fee'  => (float)($fields['cleaning_fee'] ?? 0),
                // deposit percentage (0-100)
                'deposit_pct'   => (float)($fields['deposit_pct'] ?? 40),
            ]);
        }
    }

    private function buildMonths(\DateTime $from, int $count, int $weekStart, array $blockedSet): array
    {
        $months = [];
        $blocked = array_flip($blockedSet);

        for ($i = 0; $i < $count; $i++) {
            $firstOfMonth = (clone $from)->modify("first day of +$i month");
            $monthNum     = (int)$firstOfMonth->format('n');
            $yearNum      = (int)$firstOfMonth->format('Y');
            $firstDow    = (int)$firstOfMonth->format('w');
            $startOffset = $weekStart === 1 ? (($firstDow + 6) % 7) : $firstDow;

            $gridStart = (clone $firstOfMonth)->modify("-$startOffset day");
            $days = [];

            for ($d = 0; $d < 42; $d++) {
                $cur     = (clone $gridStart)->modify("+$d day");
                $date    = $cur->format('Y-m-d');
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
                'label' => date_i18n('F Y', mktime(0, 0, 0, $monthNum, 10, $yearNum)),
            ];
        }
        return $months;
    }
}
