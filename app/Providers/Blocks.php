<?php

namespace App\Providers;

use App\Core\ServiceProvider;

class Blocks extends ServiceProvider
{
    protected const NAMESPACE = 'AcfBlocks';

    public function register(): void
    {
        $jsonPath = get_template_directory() . '/active-blocks.json';
        $blocksToLoad = [];

        // Gestion du fallback si le fichier JSON n'existe pas
        if (file_exists($jsonPath)) {
            $blocksToLoad = json_decode(file_get_contents($jsonPath), true);
        } else {
            // Assure-toi que ce fichier existe ou gère le cas vide
            $configPath = get_template_directory() . '/config/blocks.php';
            if (file_exists($configPath)) {
                $config = include $configPath;
                $blocksToLoad = $config['core'] ?? [];
            }
        }

        // Boucle optimisée
        foreach ($blocksToLoad as $slug) {
            $this->loadBlock($slug);
        }
    }

    protected function loadBlock(string $slug): void
    {
        $blocksDir = get_template_directory() . '/acf-blocks/';

        // Sécurité : On vérifie que le dossier du bloc existe
        if (!is_dir($blocksDir . $slug)) {
            return;
        }

        // 1. On charge le Controller s'il existe
        $controllerPath = $blocksDir . "{$slug}/Controller.php";
        if (file_exists($controllerPath)) {
            require_once $controllerPath;

            $className = $this->resolveClassName($slug);
            if (class_exists($className)) {
                new $className(); // Le constructeur du Controller lancera le BlockFactory::register
                return; // Si on a un Controller, on s'arrête là (BlockFactory gère le reste)
            }
        }

        // 2. Fallback : Si pas de Controller, on charge juste fields.php (mode legacy)
        // Note: C'est rare avec ton architecture BlockFactory, mais on garde par sécurité
        $fieldsPath = $blocksDir . "{$slug}/fields.php";
        if (file_exists($fieldsPath)) {
            require_once $fieldsPath;
        }
    }

    protected function resolveClassName(string $slug): string
    {
        // Transformation : "contact-form" -> "ContactForm"
        $formatted = str_replace(' ', '', ucwords(str_replace('-', ' ', $slug)));
        return self::NAMESPACE . "\\{$formatted}\\Controller";
    }
}
