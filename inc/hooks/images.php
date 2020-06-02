<?php

// Custom image size for front-end editor
add_action('after_setup_theme', function () {
    add_image_size('editor_image', 1000, 1000);
});

// Return max quality images
add_filter('jpeg_quality', function () {
    return 100;
});

// Specify MIME types
add_filter('upload_mimes', function ($mimes) {
    // TODO
    return $mimes;
});

// Mutate $sizes to return only needed images during AJAX image upload
add_filter('intermediate_image_sizes', function ($sizes) {
    if (!defined('GME_AJAX_UPLOADING') || GME_AJAX_UPLOADING === false) {
        return $sizes;
    }

    $keep_sizes = [];

    array_push($keep_sizes, 'thumbnail');
    array_push($keep_sizes, 'editor_image');

    return $keep_sizes;
});
