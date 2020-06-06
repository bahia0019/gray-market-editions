<?php

if (!function_exists('gme_image_upload')) {
    function gme_image_upload($file, $image_size = 'editor_image', $post_id = 0)
    {
        define('GME_AJAX_UPLOADING', true);

        $upload = wp_upload_bits($file['name'], null, file_get_contents($file['tmp_name']));

        $wp_filetype = wp_check_filetype(basename($upload['file']), null);

        $original_dimensions = getimagesize($upload['file']);

        if ($original_dimensions[0] > $original_dimensions[1]) {
            $original_long_dimension  = $original_dimensions[0];
            $original_short_dimension = $original_dimensions[1];
        } else {
            $original_long_dimension  = $original_dimensions[1];
            $original_short_dimension = $original_dimensions[0];
        }

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

        // If width is longer than height (it's landscape)
        if ($editor_image_dimensions[0] > $editor_image_dimensions[1]) {
            $original_width  = $original_long_dimension;
            $original_height = $original_short_dimension;
        } else { // Else height is longer than width (it's portrait)
            $original_width  = $original_short_dimension;
            $original_height = $original_long_dimension;
        }

        define('GME_AJAX_UPLOADING', false);

        return [
            'id'           => $attach_id,
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
                'width'  => $original_width,
                'height' => $original_height,
            ],
        ];
    }
}
