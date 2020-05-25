<?php

add_filter('acf/settings/save_json', function ($path) {
    $path = get_stylesheet_directory() . '/inc/acf-json';

    return $path;
});

// add_filter('acf/settings/load_json', function ($paths) {
//     $paths[] = get_stylesheet_directory() . '/inc/acf-json';

//     return $paths;
// });
