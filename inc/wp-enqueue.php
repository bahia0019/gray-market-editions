<?php

add_action('wp_enqueue_scripts', function () {
    $version = md5_file(get_stylesheet_directory() . '/mix-manifest.json');

    wp_enqueue_style('woo_photo', get_stylesheet_directory_uri() . '/dist/css/app.css', [], $version);
    wp_enqueue_script('woo_vue', get_stylesheet_directory_uri() . '/dist/js/vue.js', ['jquery'], $version, true);
    wp_enqueue_script('woo_photo', get_stylesheet_directory_uri() . '/dist/js/app.js', ['jquery', 'woo_vue'], $version, true);
});

add_action('admin_enqueue_scripts', function () {
    $version = md5_file(get_stylesheet_directory() . '/mix-manifest.json');

    wp_enqueue_style('woo_photo', get_stylesheet_directory_uri() . '/dist/css/app.css', [], $version);
});
