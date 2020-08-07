<?php

use PhpZip\ZipFile;
use BoxyBird\GME\Intervention\GMEImageFactory;

function gme_ajax_image_upload()
{
    check_ajax_referer('gme_ajax_nonce', 'nonce');

    if (!isset($_FILES) || !is_array($_FILES) || count($_FILES) > 50) {
        wp_send_json_error([
            'message' => 'files missing/too many files',
        ], 422);
    }

    $time_pre = microtime(true);

    $uploads = array_map(function ($file) {
        return gme_image_upload($file);
    }, $_FILES);

    $time_post = microtime(true);
    $exec_time = $time_post - $time_pre;

    $uploads[0]['time'] = $exec_time;

    gme_send_json(array_filter($uploads));
}
add_action('wp_ajax_gme_ajax_image_upload', 'gme_ajax_image_upload');
add_action('wp_ajax_nopriv_gme_ajax_image_upload', 'gme_ajax_image_upload');

function gme_ajax_image_process()
{
    check_ajax_referer('gme_ajax_nonce', 'nonce');

    $gme_image_data = isset($_POST['gme_image_data'])
        ? json_decode(stripslashes($_POST['gme_image_data']), true)
        : [];

    $zip_file = new ZipFile;

    $images = array_map(function ($item) use ($zip_file) {
        $gme_image = new GMEImageFactory($item['attachment_id']);
        $gme_image->process();

        $zip_file->addFile($gme_image->processed_image_path);

        return $gme_image->processed_image_url;
    }, $gme_image_data);

    $zip_url  = GME_UPLOADS_URL . $_POST['zip_file_name'] . '.zip';
    $zip_path = GME_UPLOADS_PATH . $_POST['zip_file_name'] . '.zip';
    $zip_file->saveAsFile($zip_path);

    wp_send_json([
        'zip'    => $zip_url,
        'images' => $images,
    ]);
}
add_action('wp_ajax_gme_ajax_image_process', 'gme_ajax_image_process');
