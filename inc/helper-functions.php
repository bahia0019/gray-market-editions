<?php

if (!function_exists('gme_image_upload')) {
    function gme_image_upload($file, $image_size = 'editor_image', $post_id = 0)
    {
        $upload = wp_upload_bits($file['name'], null, file_get_contents($file['tmp_name']));

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

        $thumbnail = wp_get_attachment_image_src($attach_id);
        $image     = wp_get_attachment_image_src($attach_id, $image_size);

        return [
            'id'           => $attach_id,
            'editor_image' => [
                'url'    => $image[0],
                'width'  => $image[1],
                'height' => $image[2],
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
