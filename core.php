<?php
/*
Plugin Name: WPWM OpenAI (ChatGPT) Comments Reply
Description: Ein Plugin, das OpenAI's ChatGPT verwendet, um automatisch auf Kommentare zu antworten.
Version: 1.0
Author: Volkan Kücükbudak
*/

// Plugin Options and Settings
function wpwm_openai_settings_init() {
    register_setting('wpwm_openai_settings', 'openai_api_key');
}
add_action('admin_init', 'wpwm_openai_settings_init');

function wpwm_openai_settings() {
    add_menu_page(
        'WPWM OpenAI Comment Reply Settings',
        'WPWM OpenAI Settings',
        'manage_options',
        'wpwm-openai-comment-reply-settings',
        'wpwm_openai_settings_page',
        'dashicons-format-chat'
    );
}
add_action('admin_menu', 'wpwm_openai_settings');

function wpwm_openai_settings_page() {
    $openai_api_key = get_option('openai_api_key');
    $model = get_option('model');
    $temperature = get_option('temperature');
    $max_tokens = get_option('max_tokens');
    $top_p = get_option('top_p');
    $frequency_penalty = get_option('frequency_penalty');
    $presence_penalty = get_option('presence_penalty');
    
    ?>
    <div class="wrap">
        <h1>WPWM OpenAI Comment Reply Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('wpwm_openai_settings'); ?>
            <?php do_settings_sections('wpwm_openai_settings'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">OpenAI API Key</th>
                    <td>
                        <input type="password" required name="openai_api_key" class="regular-text" value="<?php echo esc_attr($openai_api_key); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Model</th>
                    <td>
                        <select name="model">
                            <option <?php selected($model, 'text-davinci-003'); ?> value="text-davinci-003">text-davinci-003</option>
                            <!-- Add other models here -->
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Temperature</th>
                    <td><input type="number" step="0.01" min="0" max="1" name="temperature" value="<?php echo esc_attr($temperature); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Max Tokens</th>
                    <td><input type="number" min="1" name="max_tokens" value="<?php echo esc_attr($max_tokens); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Top P</th>
                    <td><input type="number" step="0.01" min="0" max="1" name="top_p" value="<?php echo esc_attr($top_p); ?>" /></td>
                </tr>
