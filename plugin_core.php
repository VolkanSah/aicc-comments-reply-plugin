<?php
/*
 * Plugin Name:       A.I Comments Reply for GPT
 * Plugin URI:        https://aicodecraft.io
 * Description:       Effortlessly manage and respond to comments on your WordPress site with the power of AI using the ChatGPT Comments Reply Plugin
 * Version:           1.2
 * Requires at least: 5.2
 * Requires PHP:      7.4
 * Author:            S. Volkan Kücükbudak
 * Author URI:        https://aicodecraft.io
 * License:           CC BY 4.0
 * License URI:       https://creativecommons.org/licenses/by/4.0/
 * Update URI:        https://github.com/VolkanSah/ChatGPT-Comments-Reply-WordPress-Plugin/latest.zip
 * Text Domain:       aicc-aicr
 * Domain Path:       /languages
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

function aicc_openai_settings_init() {
    register_setting('aicc_openai_settings', 'openai_api_key');
    register_setting('aicc_openai_settings', 'model');
    register_setting('aicc_openai_settings', 'temperature');
    register_setting('aicc_openai_settings', 'max_tokens');
    register_setting('aicc_openai_settings', 'top_p');
    register_setting('aicc_openai_settings', 'frequency_penalty');
    register_setting('aicc_openai_settings', 'presence_penalty');
}
add_action('admin_init', 'aicc_openai_settings_init');

function aicc_openai_settings() {
    add_menu_page(
        'AICC OpenAI Comment Reply Settings',
        'AICC OpenAI Settings',
        'manage_options',
        'aicc-openai-comment-reply-settings',
        'aicc_openai_settings_page',
        'dashicons-format-chat'
    );
}
add_action('admin_menu', 'aicc_openai_settings');

function aicc_openai_settings_page() {
    $openai_api_key = get_option('openai_api_key');
    $model = get_option('model');
    $temperature = get_option('temperature');
    $max_tokens = get_option('max_tokens');
    $top_p = get_option('top_p');
    $frequency_penalty = get_option('frequency_penalty');
    $presence_penalty = get_option('presence_penalty');
    ?>
    <div class="wrap">
        <h1>AICC OpenAI Comment Reply Settings</h1>
        <div class="aicc-openai-info">
            <h2>About AICC OpenAI Comment Reply Plugin</h2>
            <p>This plugin uses OpenAI's ChatGPT to automatically reply to comments on your WordPress website. It leverages AI technology to generate relevant and helpful responses to your visitors' comments.</p>

            <h3>How to use the plugin:</h3>
            <ol>
                <li>Enter your OpenAI API key in the "OpenAI API Key" field and save the settings.</li>
                <li>Go to the comments management in your WordPress admin area.</li>
                <li>In the comment list, you'll see the option "Reply with AICC OpenAI" under each comment. Click on this option to generate an AI-generated response to the comment.</li>
            </ol>

            <h3>Recommended settings:</h3>
            <p>These are the recommended settings for the plugin:</p>
            <ul>
                <li>Model: gpt-3.5-turbo</li>
                <li>Temperature: 0.6</li>
                <li>Max Tokens: 500</li>
                <li>Top P: 1</li>
                <li>Frequency Penalty: 0.0</li>
                <li>Presence Penalty: 0.0</li>
            </ul>
            <p>These settings have been tested to provide good results with the plugin. However, feel free to adjust them according to your needs and preferences.</p>
        </div>
        <form method="post" action="options.php">
            <?php settings_fields('aicc_openai_settings'); ?>
            <?php do_settings_sections('aicc_openai_settings'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">OpenAI API Key</th>
                    <td>
                        <input type="password" required name="openai_api_key" class="regular-text" value="<?php echo esc_attr($openai_api_key); ?>" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Model</th>
                    <td>
                        <select name="model">
                            <option <?php selected($model, 'gpt-3.5-turbo'); ?> value="gpt-3.5-turbo">gpt-3.5-turbo</option>
                            <option <?php selected($model, 'gpt-3.5-turbo-16k'); ?> value="gpt-3.5-turbo-16k">gpt-3.5-turbo-16k</option>
                            <option <?php selected($model, 'gpt-4'); ?> value="gpt-4">gpt-4</option>
                            <option <?php selected($model, 'gpt-4-turbo'); ?> value="gpt-4-turbo">gpt-4-turbo</option>
                            <option <?php selected($model, 'gpt-4-0613'); ?> value="gpt-4-0613">gpt-4-0613</option>
                            <option <?php selected($model, 'gpt-4-0125-preview'); ?> value="gpt-4-0125-preview">gpt-4-0125-preview</option>
                            <option <?php selected($model, 'gpt-4-turbo-preview'); ?> value="gpt-4-turbo-preview">gpt-4-turbo-preview</option>
                            <option <?php selected($model, 'gpt-4o-mini'); ?> value="gpt-4o-mini">gpt-4o-mini</option>
                            <option <?php selected($model, 'gpt-4o-mini-2024-07-18'); ?> value="gpt-4o-mini-2024-07-18">gpt-4o-mini-2024-07-18</option>
                            <option <?php selected($model, 'gpt-4-1106-preview'); ?> value="gpt-4-1106-preview">gpt-4-1106-preview</option>
                            <option <?php selected($model, 'gpt-4o'); ?> value="gpt-4o">gpt-4o</option>
                            <option <?php selected($model, 'gpt-4o-2024-05-13'); ?> value="gpt-4o-2024-05-13">gpt-4o-2024-05-13</option>
                            <option <?php selected($model, 'gpt-4o-2024-08-06'); ?> value="gpt-4o-2024-08-06">gpt-4o-2024-08-06</option>
                            <option <?php selected($model, 'chatgpt-4o-latest'); ?> value="chatgpt-4o-latest">chatgpt-4o-latest</option>
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

// Adds button to comment row actions
function aicc_add_button_to_comment_row_actions($actions, $comment) {
    $actions['aicc_reply'] = '<a href="#" class="openai-reply">Reply with A.I</a>';
    return $actions;
}
add_filter('comment_row_actions', 'aicc_add_button_to_comment_row_actions', 10, 2);

function aicc_add_js_to_comment_page() {
    ?>
   <script>
        jQuery(document).ready(function ($) {
            $('.openai-reply').click(function () {
                var commentId = $(this).closest('tr').attr('id').replace('comment-', '');
                $('#comment-' + commentId + ' .row-actions .reply button').click();
                aicc_openai_reply(commentId);
                return false;
            });

            function aicc_openai_reply(comment_id) {
                var rowData = $('#inline-' + comment_id);
                var comment_text = $('textarea.comment', rowData).val();
                var editRow = $('#replyrow');
                $('#replysubmit .spinner').addClass('is-active');

                $.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: {
                        action: 'aicc_generate_reply',
                        comment_text: comment_text,
                        comment_id: comment_id,
                    },
                    success: function (response) {
                        if(response.success) {
                            $('#replycontent', editRow).val(response.data.reply);
                            $('#replysubmit .spinner').removeClass('is-active');
                        } else {
                            console.error('Error: ' + response.data.message);
                            alert('Error: ' + response.data.message);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error("AJAX error: " + textStatus + ' : ' + errorThrown);
                    }
                });
            }
        });
    </script>
    <?php
}
add_action('admin_footer-edit-comments.php', 'aicc_add_js_to_comment_page');

function aicc_generate_reply() {
    $comment_text = sanitize_text_field($_POST['comment_text']);
    $openai_api_key = get_option('openai_api_key');
    $model = get_option('model');
    $temperature = floatval(get_option('temperature'));
    $max_tokens = intval(get_option('max_tokens'));
    $top_p = floatval(get_option('top_p'));
    $frequency_penalty = floatval(get_option('frequency_penalty'));
    $presence_penalty = floatval(get_option('presence_penalty'));

    // Log initial data for debugging
    error_log('AICC Generate Reply Triggered');
    error_log('Comment Text: ' . $comment_text);
    error_log('Model: ' . $model);

    $response = wp_remote_post('https://api.openai.com/v1/chat/completions', [
        'headers' => [
            'Authorization' => 'Bearer ' . $openai_api_key,
            'Content-Type'  => 'application/json',
        ],
        'body'    => json_encode([
            'model'             => $model,
            'messages'          => [
                ['role' => 'system', 'content' => 'You are a helpful assistant.'],
                ['role' => 'user', 'content' => $comment_text]
            ],
            'max_tokens'        => $max_tokens,
            'temperature'       => $temperature,
            'top_p'             => $top_p,
            'frequency_penalty' => $frequency_penalty,
            'presence_penalty'  => $presence_penalty,
        ]),
        'timeout' => 30, // Timeout auf 30 Sekunden erhöhen
    ]);

    if (is_wp_error($response)) {
        error_log('Error in OpenAI request: ' . $response->get_error_message());
        wp_send_json_error(['message' => $response->get_error_message()]);
    }

    $body = wp_remote_retrieve_body($response);
    $result = json_decode($body, true);

    // Log the response for debugging
    error_log('OpenAI Response: ' . print_r($result, true));

    if (isset($result['choices'][0]['message']['content'])) {
        wp_send_json_success(['reply' => $result['choices'][0]['message']['content']]);
    } else {
        error_log('Failed to generate a reply.');
        wp_send_json_error(['message' => 'Failed to generate a reply.']);
    }
}

add_action('wp_ajax_aicc_generate_reply', 'aicc_generate_reply');
