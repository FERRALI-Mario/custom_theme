<?php
/**
 * Bootstrap the theme.
 *
 * Loads Composer autoloader and initializes the theme.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Composer autoloader
require_once __DIR__ . '/vendor/autoload.php';

use App\Core\Theme;

// Initialize the theme (sets up theme support, menus, assets, Timber, blocks…)
Theme::init();
