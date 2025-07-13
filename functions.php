<?php
/**
 * Point d’entrée du thème
 */

if (!defined('ABSPATH')) {
    exit; // Sécurité : empêche l'accès direct
}

// Chargement de l'autoloader Composer (PSR-4)
require_once __DIR__ . '/vendor/autoload.php';

use App\Core\Theme;

// Démarrage du thème
Theme::init();
