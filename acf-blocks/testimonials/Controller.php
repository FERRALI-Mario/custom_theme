<?php

namespace AcfBlocks\Testimonials;

use App\Core\BlockFactory;
use Timber\Timber;

class Controller extends BlockFactory
{
    private const MIN_TRACK_ITEMS = 12; // nombre minimum de cartes par piste

    public function __construct()
    {
        parent::__construct('testimonials');
    }

    /**
     * Render : prépare le contexte (duplication serveurside + durée)
     */
    public function render(array $block): void
    {
        // Aperçu ACF si disponible
        $previewPath = $this->getPreviewPath();
        if ($this->isPreview($block) && $previewPath) {
            $previewUrl = get_template_directory_uri() . '/' . $previewPath;
            echo '<img src="' . esc_url($previewUrl) . '" style="width:100%;height:auto;" alt="Aperçu du bloc" />';
            return;
        }

        $context = $this->prepareContext($block);
        Timber::render($this->getTemplatePath(), $context);
    }

    public function getTitle(): string
    {
        return 'Témoignages clients';
    }

    public function getDescription(): string
    {
        return 'Montre les retours ou avis de clients pour renforcer la confiance.';
    }

    public function getCategory(): string
    {
        return 'relations';
    }

    public function getKeywords(): array
    {
        return ['testimonials', 'avis', 'clients'];
    }

    public function getIcon(): string
    {
        return 'star-filled';
    }

    /**
     * Prépare le contexte pour la vue Twig.
     */
    protected function prepareContext(array $block): array
    {
        $context = Timber::context();
        $context['block']   = $block;
        $context['fields']  = $this->getFields();

        $original = $context['fields']['testimonials'] ?? [];
        $extended = $this->repeatToMin($original, self::MIN_TRACK_ITEMS);

        $context['testimonials'] = $extended;

        // Durée proportionnelle au nombre de cartes (après extension)
        // Ajuste le ratio si tu veux une vitesse différente.
        $count    = count($extended);
        $duration = max(8, (int)ceil(($count / 6) * 19)); // base : ~13s par 6 cartes
        $context['marquee_duration'] = $duration;

        return $context;
    }

    /**
     * Duplique un tableau circulairement jusqu'à atteindre au moins $min éléments.
     * - Si $items est vide : retourne [].
     * - Conserve l'ordre d'origine en boucle.
     */
    private function repeatToMin(array $items, int $min): array
    {
        $count = count($items);
        if ($count === 0) {
            return [];
        }
        if ($count >= $min) {
            return $items;
        }

        $result = $items;
        while (count($result) < $min) {
            // On ne concatène pas une copie énorme à chaque tour pour éviter un gros jump mémoire
            $needed = $min - count($result);
            // clone le minimum entre $count et $needed
            $chunk = array_slice($items, 0, min($count, $needed));
            $result = array_merge($result, $chunk);
        }
        return $result;
    }
}
