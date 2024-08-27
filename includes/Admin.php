<?php
namespace AICC;

if ( ! defined( 'ABSPATH' ) ) exit;

class Admin {

    private $plugin_name;
    private $version;

    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;

        // Register settings during admin init
        add_action('admin_init', [$this, 'register_settings']);
        // Add admin menu
        add_action('admin_menu', [$this, 'add_admin_menu']);
    }

    public function add_admin_menu() {
        add_menu_page(
            __('AI Commenter', 'aicc'), 
            __('AI Commenter', 'aicc'), 
            'manage_options', 
            'aicc', 
            [$this, 'render_settings_page'], 
            'dashicons-admin-generic', 
            6
        );
    }

    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('AI Commenter Settings', 'aicc'); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('aicc_settings'); // Sicherheitsfelder und Nonce
                do_settings_sections('aicc'); // Die Sektionen und Felder aus register_settings() anzeigen
                submit_button(); // Speichern-Button anzeigen
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

        $this->add_settings_field('openai_api_key', __('API Key', 'aicc'), 'render_api_key_field');
        $this->add_settings_field('model', __('Model', 'aicc'), 'render_model_field');
        $this->add_settings_field('temperature', __('Temperature', 'aicc'), 'render_temperature_field');
        $this->add_settings_field('max_tokens', __('Max Tokens', 'aicc'), 'render_max_tokens_field');
        $this->add_settings_field('top_p', __('Top P', 'aicc'), 'render_top_p_field');
        $this->add_settings_field('frequency_penalty', __('Frequency Penalty', 'aicc'), 'render_frequency_penalty_field');
        $this->add_settings_field('presence_penalty', __('Presence Penalty', 'aicc'), 'render_presence_penalty_field');
    }

    private function add_settings_field($id, $title, $callback) {
        add_settings_field(
            $id,
            $title,
            [$this, $callback],
            'aicc',
            'aicc_section'
        );
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

    public function render_max_tokens_field() {
        $value = get_option('max_tokens');
        echo '<input type="number" min="1" name="max_tokens" value="' . esc_attr($value) . '" class="small-text">';
    }

    public function render_top_p_field() {
        $value = get_option('top_p');
        echo '<input type="number" step="0.1" min="0" max="1" name="top_p" value="' . esc_attr($value) . '" class="small-text">';
    }

    public function render_frequency_penalty_field() {
        $value = get_option('frequency_penalty');
        echo '<input type="number" step="0.1" min="0" max="1" name="frequency_penalty" value="' . esc_attr($value) . '" class="small-text">';
    }

    public function render_presence_penalty_field() {
        $value = get_option('presence_penalty');
        echo '<input type="number" step="0.1" min="0" max="1" name="presence_penalty" value="' . esc_attr($value) . '" class="small-text">';
    }
}
