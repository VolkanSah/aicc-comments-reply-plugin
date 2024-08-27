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
    }

    private function define_admin_hooks() {
        $admin = new Admin($this->plugin_name, $this->version);
        add_action('admin_enqueue_scripts', [$admin, 'enqueue_styles']);
        add_action('admin_enqueue_scripts', [$admin, 'enqueue_scripts']);
        add_action('add_meta_boxes', [$admin, 'add_metabox']);
        add_action('save_post', [$admin, 'save_metabox']);
        add_action('admin_menu', [$admin, 'add_admin_menu']);
    }
}
