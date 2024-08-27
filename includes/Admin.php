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
    }

    public function enqueue_scripts() {
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/admin.js', ['jquery'], $this->version, false);
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
        if (!isset($_POST['aicc_metabox_nonce']) || !wp_verify_nonce($_POST['aicc_metabox_nonce'], 'aicc_save_metabox')) {
            return $post_id;
        }
        // Meta box saving logic here
    }

    public function add_admin_menu() {
        add_menu_page('AI Commenter', 'AI Commenter', 'manage_options', 'aicc', [$this, 'render_settings_page'], 'dashicons-admin-generic', 6);
    }

    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('AI Commenter Settings', 'aicc'); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('aicc_settings');
                do_settings_sections('aicc');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public function register_settings() {
        register_setting('aicc_settings', 'openai_api_key');
        register_setting('aicc_settings', 'model');
        register_setting('aicc_settings', 'temperature');
        register_setting('aicc_settings', 'max_tokens');
        register_setting('aicc_settings', 'top_p');
        register_setting('aicc_settings', 'frequency_penalty');
        register_setting('aicc_settings', 'presence_penalty');

        add_settings_section(
            'aicc_section',
            __('OpenAI API Settings', 'aicc'),
            null,
            'aicc'
        );

        add_settings_field(
            'openai_api_key',
            __('API Key', 'aicc'),
            [$this, 'render_api_key_field'],
            'aicc',
            'aicc_section'
        );

        add_settings_field(
            'model',
            __('Model', 'aicc'),
            [$this, 'render_model_field'],
            'aicc',
            'aicc_section'
        );

        add_settings_field(
            'temperature',
            __('Temperature', 'aicc'),
            [$this, 'render_temperature_field'],
            'aicc',
            'aicc_section'
        );

        // Add other fields in a similar way
    }

    public function render_api_key_field() {
        $value = get_option('openai_api_key');
        echo '<input type="password" name="openai_api_key" value="' . esc_attr($value) . '" class="regular-text">';
    }

    public function render_model_field() {
        $value = get_option('model');
        ?>
        <select name="model">
            <option value="gpt-3.5-turbo" <?php selected($value, 'gpt-3.5-turbo'); ?>>gpt-3.5-turbo</option>
            <option value="gpt-4" <?php selected($value, 'gpt-4'); ?>>gpt-4</option>
            <option value="gpt-4-32k" <?php selected($value, 'gpt-4-32k'); ?>>gpt-4-32k</option>
        </select>
        <?php
    }

    public function render_temperature_field() {
        $value = get_option('temperature');
        echo '<input type="number" step="0.1" min="0" max="1" name="temperature" value="' . esc_attr($value) . '" class="small-text">';
    }

    // Similar methods for other fields
}

