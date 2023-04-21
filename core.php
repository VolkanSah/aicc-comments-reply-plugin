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
                        "model": "text-davinci-003",
                        "prompt": 'Reply to comment: ' + comment_text,
                        "max_tokens": 1024,
                        "temperature": 0.5
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


