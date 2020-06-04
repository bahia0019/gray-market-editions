<?php

function gme_ajax_image_upload()
{
    check_ajax_referer('gme_ajax_nonce', 'nonce');

    if (!isset($_FILES) || !is_array($_FILES) || count($_FILES) > 50) {
        wp_send_json_error([
            'message' => 'files missing/too many files',
        ], 422);
    }

    $uploads = array_map(function ($file) {
        return gme_image_upload($file);
    }, $_FILES);

    // Testing
    // array_walk($uploads, function ($upload) {
    //     update_post_meta($upload['id'], 'gme_image_transformations', [
    //         'width'  => random_int(500, 600),
    //         'height' => random_int(500, 600),
    //         'rotate' => [0, 90, 180, 270][random_int(0, 3)],
    //     ]);

    //     $gme_image = new \BoxyBird\GME\GMEImageFactory($upload['id']);

    //     $gme_image->process();
    // });

    wp_send_json($uploads);
}
add_action('wp_ajax_gme_ajax_image_upload', 'gme_ajax_image_upload');
add_action('wp_ajax_nopriv_gme_ajax_image_upload', 'gme_ajax_image_upload');

function gme_ajax_image_process()
{
    check_ajax_referer('gme_ajax_nonce', 'nonce');

    $gme_image_data = isset($_POST['gme_image_data'])
        ? json_decode(stripslashes($_POST['gme_image_data']), true)
        : [];

    $res = array_map(function ($item) {
        $gme_image = new \BoxyBird\GME\GMEImageFactory($item['attachment_id']);
        $gme_image->process();

        return $gme_image->processed_image_url;
    }, $gme_image_data);

    wp_send_json($res);
}
add_action('wp_ajax_gme_ajax_image_process', 'gme_ajax_image_process');
