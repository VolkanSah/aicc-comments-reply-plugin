<?php
/*
 * Plugin Name:       Volkans - Comments Reply with ChatGPT (OpenAi)
 * Plugin URI:        https://github.com/VolkanSah/ChatGPT-Comments-Reply-WordPress-Plugin/
 * Description:       Effortlessly manage and respond to comments on your WordPress site with the power of AI using the ChatGPT Comments Reply Plugin
 * Version:           1.0
 * Requires at least: 5.2
 * Requires PHP:      7.4
 * Author:            S. Volkan Kücükbudak
 * Author URI:        https://volkansah.github.com
 * License:           CC BY 4.0
 * License URI:       https://creativecommons.org/licenses/by/4.0/
 * Update URI:       https://github.com/VolkanSah/ChatGPT-Comments-Reply-WordPress-Plugin/
 * Text Domain:       wpwm-crgpt
 * Domain Path:       /languages
 */

// Plugin Options and Settings
function wpwm_openai_settings_init() {
    register_setting('wpwm_openai_settings', 'openai_api_key');
    register_setting('wpwm_openai_settings', 'model');
    register_setting('wpwm_openai_settings', 'temperature');
    register_setting('wpwm_openai_settings', 'max_tokens');
    register_setting('wpwm_openai_settings', 'top_p');
    register_setting('wpwm_openai_settings', 'frequency_penalty');
    register_setting('wpwm_openai_settings', 'presence_penalty');
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
        <div class="wpwm-openai-info">
    <h2>About WPWM OpenAI Comment Reply Plugin</h2>
    <p>This plugin uses OpenAI's ChatGPT to automatically reply to comments on your WordPress website. It leverages AI technology to generate relevant and helpful responses to your visitors' comments.</p>
    
    <h3>How to use the plugin:</h3>
    <ol>
        <li>Enter your OpenAI API key in the "OpenAI API Key" field and save the settings.</li>
        <li>Go to the comments management in your WordPress admin area.</li>
        <li>In the comment list, you'll see the option "Reply with WPWM OpenAI" under each comment. Click on this option to generate an AI-generated response to the comment.</li>
    </ol>

    <h3>Recommended settings:</h3>
    <p>These are the recommended settings for the plugin:</p>
    <ul>
        <li>Model: text-davinci-003</li>
        <li>Temperature: 0.9</li>
        <li>Max Tokens: 1000</li>
        <li>Top P: 1</li>
        <li>Frequency Penalty: 0.0</li>
        <li>Presence Penalty: 0.0</li>
    </ul>
    <p>These settings have been tested to provide good results with the plugin. However, feel free to adjust them according to your needs and preferences.</p>
</div>
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
                            <!--  other models soon, sorry testing cost money  -->
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
                <tr valign="top">
                    <th scope="row">Frequency Penalty</th>
                    <td><input type="number" step="0.01" min="0" max="1" name="frequency_penalty" value="<?php echo esc_attr($frequency_penalty); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Presence Penalty</th>
                    <td><input type="number" step="0.01" min="0" max="1" name="presence_penalty" value="<?php echo esc_attr($presence_penalty); ?>" /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}
// Adds button to comment line actions
function wpwm_openai_add_button_to_comment_row_actions($actions, $comment) {
    $actions['wpwm_openai_reply'] = '<a href="#" class="openai-reply">Reply with WPWM OpenAI</a>';
    return $actions;
}
add_filter('comment_row_actions', 'wpwm_openai_add_button_to_comment_row_actions', 10, 2);
// Adds JavaScript code to process OpenAI responses
function wpwm_openai_add_js_to_comment_page() {
    $openai_api_key = get_option('openai_api_key');
    $model = get_option('model');
    $temperature = get_option('temperature');
    $max_tokens = get_option('max_tokens');
    $top_p = get_option('top_p');
    $frequency_penalty = get_option('frequency_penalty');
    $presence_penalty = get_option('presence_penalty');
    ?>
   <script>
        jQuery(document).ready(function ($) {
            // Adds click handlers to "Reply with WPWM OpenAI" button
            $('.openai-reply').click(function () {
                var commentId = $(this).closest('tr').attr('id').replace('comment-', '');
                $('#comment-' + commentId + ' .row-actions .reply button').click();
                wpwm_openai_reply(commentId);
                return false;
            });

            function wpwm_openai_reply(comment_id) {
                rowData = $('#inline-' + comment_id);
                comment_text = $('textarea.comment', rowData).val();
                editRow = $('#replyrow');
                $('#replysubmit .spinner').addClass('is-active');
                apiKey = "<?php echo esc_attr($openai_api_key); ?>";
                $.ajax({
                    type: "POST",
                    url: "https://api.openai.com/v1/completions",
                    headers: {
                        "Content-Type": "application/json",
                        "Authorization": "Bearer " + apiKey
                    },
                    data: JSON.stringify({
                        "model": "<?php echo esc_attr($model); ?>",
                        "prompt": 'Reply to comment: ' + comment_text,
                        "max_tokens": <?php echo esc_attr($max_tokens); ?>,
                        "temperature": <?php echo esc_attr($temperature); ?>,
                        "top_p": <?php echo esc_attr($top_p); ?>,
                        "frequency_penalty": <?php echo esc_attr($frequency_penalty); ?>,
                        "presence_penalty": <?php echo esc_attr($presence_penalty); ?>
                    }),            
                       success: function (response) {
                        var choices = response.choices;
                        if (choices.length > 0) {
                            var choice = choices[0];
                            var reply_text = choice.text;
                            $('#replycontent', editRow).val(reply_text);
                            $('#replysubmit .spinner').removeClass('is-active');
                        }
                    }
                });
            }
        });
    </script>
    <?php
}
add_action('admin_footer-edit-comments.php', 'wpwm_openai_add_js_to_comment_page');
// end wpwm
