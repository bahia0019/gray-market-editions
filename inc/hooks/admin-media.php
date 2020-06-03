<?php

// Delete custom image when main attachment is deleted
add_action('delete_attachment', function ($post_id) {
    wp_delete_file(GME_UPLOADS_PATH . $post_id . '.jpg');
});
