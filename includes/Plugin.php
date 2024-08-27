<?php
namespace AICC;

if ( ! defined( 'ABSPATH' ) ) exit;

class Plugin {

    private static $instance = null;
    private $version = '1.0.0';
    private $plugin_name = 'aicc';

    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->define_admin_hooks();
        $this->load_default_options();
    }

    private function define_admin_hooks() {
        $admin = new Admin($this->plugin_name, $this->version);
        add_action('admin_enqueue_scripts', [$admin, 'enqueue_styles']);
        add_action('admin_enqueue_scripts', [$admin, 'enqueue_scripts']);
        add_action('add_meta_boxes', [$admin, 'add_metabox']);
        add_action('save_post', [$admin, 'save_metabox']);
        add_action('admin_menu', [$admin, 'add_admin_menu']);
    }

    private function load_default_options() {
        $default_options = [
            'openai_api_key' => '',
            'model' => 'gpt-3.5-turbo',
            'temperature' => 0.7,
            'max_tokens' => 1000,
            'top_p' => 1.0,
            'frequency_penalty' => 0.0,
            'presence_penalty' => 0.0,
        ];

        foreach ($default_options as $key => $value) {
            if (get_option($key) === false) {
                update_option($key, $value);
            }
        }
    }
}
