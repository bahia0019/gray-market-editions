<?php

use Intervention\Image\ImageManager;

if (!function_exists('gme_image_upload')) {
    function gme_image_upload($file, $image_size = 'editor_image', $post_id = 0)
    {
        global $gme_ajax_uploading;

        $gme_ajax_uploading = true;

        $image_data = (new ImageManager)
            ->make($file['tmp_name'])
            ->orientate()
            ->response();

        $upload = wp_upload_bits($file['name'], null, $image_data);

        $wp_filetype = wp_check_filetype(basename($upload['file']), null);

        $original_dimensions = getimagesize($upload['file']);

        $attachment = [
            'post_mime_type' => $wp_filetype['type'],
            'post_title'     => sanitize_file_name($file['name']),
            'post_content'   => '',
            'post_status'    => 'inherit',
        ];

        $attach_id = wp_insert_attachment($attachment, $upload['file'], $post_id);

        require_once ABSPATH . 'wp-admin/includes/image.php';

        $attach_data = wp_generate_attachment_metadata($attach_id, $upload['file']);
        wp_update_attachment_metadata($attach_id, $attach_data);

        $thumbnail               = wp_get_attachment_image_src($attach_id);
        $editor_image            = wp_get_attachment_image_src($attach_id, $image_size);
        $editor_image_dimensions = getimagesize($editor_image[0]);
        $short_name              = '...' . substr($file['name'], -10);

        $gme_ajax_uploading = false;

        return [
            'id'           => $attach_id,
            'short_name'   => $short_name,
            'editor_image' => [
                'url'    => $editor_image[0],
                'width'  => $editor_image_dimensions[0],
                'height' => $editor_image_dimensions[1],
            ],
            'thumbnail'  => [
                'url'    => $thumbnail[0],
                'width'  => $thumbnail[1],
                'height' => $thumbnail[2],
            ],
            'original' => [
                'url'    => $upload['url'],
                'width'  => $original_dimensions[0],
                'height' => $original_dimensions[1],
            ],
        ];
    }
}

if (!function_exists('gme_send_json')) {
    function gme_send_json(array $data)
    {
        $data = json_encode($data);

        header('Content-Type: application/json');

        // Checks if gzip is supported by client
        $pack = true;

        if (empty($_SERVER['HTTP_ACCEPT_ENCODING']) || strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') === false) {
            $pack = false;
        }

        // If supported, gzips data
        if ($pack) {
            header('Content-Encoding: gzip');

            $data = gzencode($data, 9, FORCE_GZIP);
        }

        // Compressed or not, sets the Content-Length
        header('Content-Length: ' . strlen($data, 'latin1'));

        echo $data;
        exit;
    }
}
