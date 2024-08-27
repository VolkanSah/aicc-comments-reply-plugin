<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Plugin Name:       AICC - AI Content Commenter
 * Plugin URI:        https://aipower.org
 * Description:       Automate comment responses with AI.
 * Version:           1.0.0
 * Author:            Senol Sahin
 * Author URI:        https://aipower.org
 * Text Domain:       aicc
 */

// Autoload classes
spl_autoload_register(function ($class) {
    $prefix = 'AICC\\';
    $base_dir = __DIR__ . '/includes/';
    $len = strlen($prefix);

    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Initialize the plugin
function aicc_init() {
    AICC\Plugin::get_instance();
}
add_action('plugins_loaded', 'aicc_init');
