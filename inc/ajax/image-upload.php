<?php

function gme_ajax_image_upload()
{
    check_ajax_referer('gme_ajax_nonce', 'nonce');

    if (!isset($_FILES) || !is_array($_FILES)) {
        wp_send_json_error([
            'message' => 'images files missing/error',
        ], 422);
    }

    $uploads = array_map(function ($file) {
        return gme_image_upload($file);
    }, $_FILES);

    wp_send_json($uploads);
}
add_action('wp_ajax_gme_ajax_image_upload', 'gme_ajax_image_upload');
add_action('wp_ajax_nopriv_gme_ajax_image_upload', 'gme_ajax_image_upload');
