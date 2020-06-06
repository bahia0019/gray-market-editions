<?php

// Delete custom image when main attachment is deleted
add_action('delete_attachment', function ($post_id) {
    $file_name = get_post_meta($post_id, 'gme_image_file_name', true);

    wp_delete_file(GME_UPLOADS_PATH . $file_name);
});
