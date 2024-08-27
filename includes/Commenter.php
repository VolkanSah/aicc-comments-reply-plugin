<?php
namespace AICC;

if ( ! defined( 'ABSPATH' ) ) exit;

class Commenter {

    public function generate_reply($comment_id) {
        $comment = get_comment($comment_id);
        if (!$comment) {
            return new \WP_Error('invalid_comment', __('Invalid comment ID', 'aicc'));
        }

        $prompt = "Please generate a thoughtful response to the following comment: " . $comment->comment_content;
        $response = $this->send_to_openai($prompt);

        if (is_wp_error($response)) {
            return $response;
        }

        return $response['choices'][0]['message']['content'];
    }

    private function send_to_openai($prompt) {
        $api_key = get_option('openai_api_key');
        $model = get_option('model');
        $temperature = get_option('temperature');
        $max_tokens = get_option('max_tokens');
        $top_p = get_option('top_p');
        $frequency_penalty = get_option('frequency_penalty');
        $presence_penalty = get_option('presence_penalty');

        $response = wp_remote_post('https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . $api_key,
                'Content-Type'  => 'application/json',
            ],
            'body'    => json_encode([
                'model'             => $model,
                'messages'          => [
                    ['role' => 'system', 'content' => 'You are a helpful assistant.'],
                    ['role' => 'user', 'content' => $prompt]
                ],
                'max_tokens'        => $max_tokens,
                'temperature'       => $temperature,
                'top_p'             => $top_p,
                'frequency_penalty' => $frequency_penalty,
                'presence_penalty'  => $presence_penalty,
            ]),
        ]);

        if (is_wp_error($response)) {
            return $response;
        }

        $body = wp_remote_retrieve_body($response);
        return json_decode($body, true);
    }
}
