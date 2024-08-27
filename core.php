<?php
/*
 * Plugin Name:       A.I Comments Reply for GPT
 * Plugin URI:        https://github.com/VolkanSah/ChatGPT-Comments-Reply-WordPress-Plugin/
 * Description:       Effortlessly manage and respond to comments on your WordPress site with the power of AI using the ChatGPT Comments Reply Plugin
 * Version:           2.0
 * Requires at least: 5.2
 * Requires PHP:      7.4
 * Author:            S. Volkan Kücükbudak
 * Author URI:        https://volkansah.github.com
 * License:           CC BY 4.0
 * License URI:       https://creativecommons.org/licenses/by/4.0/
 * Update URI:        https://github.com/VolkanSah/ChatGPT-Comments-Reply-WordPress-Plugin/latest.zip
 * Text Domain:       aicc-aicr
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Load required files
require_once plugin_dir_path(__FILE__) . 'options.php';
require_once plugin_dir_path(__FILE__) . 'plugin-class.php';

// Initialize the plugin
function run_aicc_comment_reply_plugin() {
    $plugin = new AICC_Comment_Reply_Plugin();
    $plugin->run();
}
add_action('plugins_loaded', 'run_aicc_comment_reply_plugin');
