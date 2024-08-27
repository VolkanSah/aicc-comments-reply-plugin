<?php
namespace AICC;

if ( ! defined( 'ABSPATH' ) ) exit;

class Admin {

    private $plugin_name;
    private $version;

    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    public function enqueue_styles() {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/admin.css', [], $this->version, 'all');
        wp_enqueue_style('font-awesome', plugin_dir_url(__FILE__) . 'css/font-awesome.min.css', [], $this->version, 'all');
    }

    public function enqueue_scripts() {
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/admin.js', ['jquery'], $this->version, false);
        wp_enqueue_script('jquery-ui-sortable');
    }

    public function add_metabox() {
        $screens = ['post', 'page'];
        foreach ($screens as $screen) {
            add_meta_box('aicc_metabox', __('AI Comment Response', 'aicc'), [$this, 'render_metabox'], $screen, 'advanced', 'default');
        }
    }

    public function render_metabox($post) {
        include plugin_dir_path(__FILE__) . 'views/metabox.php';
    }

    public function save_metabox($post_id) {
        if (isset($_POST['aicc_metabox_nonce']) && !wp_verify_nonce($_POST['aicc_metabox_nonce'], 'aicc_save_metabox')) {
            return $post_id;
        }
        // Meta box saving logic here
    }

    public function add_admin_menu() {
        add_menu_page('AI Commenter', 'AI Commenter', 'manage_options', 'aicc', [$this, 'render_settings_page'], 'dashicons-admin-generic', 6);
    }

    public function render_settings_page() {
        include plugin_dir_path(__FILE__) . 'views/settings.php';
    }
}
