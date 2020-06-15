<?php

add_action('gme_after_editor', function () {
    global $product;

    if ($product->get_name() !== 'Posters') {
        return;
    }

    require GME_TEMPLATES_PATH . 'posters-download-grid.php';
}, 5);
