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
});

// Mutate $sizes to return only needed images
add_filter('intermediate_image_sizes', function ($sizes) {
    $keep_sizes = [];

    array_push($keep_sizes, 'full');
    array_push($keep_sizes, 'thumbnail');
    array_push($keep_sizes, 'editor_image');
    array_push($keep_sizes, 'shop_single');
    array_push($keep_sizes, 'shop_thumbnail');
    array_push($keep_sizes, 'woocommerce_single');
    array_push($keep_sizes, 'woocommerce_thumbnail');

    return $keep_sizes;
});
