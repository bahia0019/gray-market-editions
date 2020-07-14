<div class="gme-image-mini-grid">
    <p class="gme-image-mini-grid__title">Images</p>
    <div class="gme-image-mini-grid__grid">
        <?php foreach ($gme_image_data as $data): ?>
            <img class="gme-image-mini-grid__image" src="<?php echo wp_get_attachment_image_url($data['attachment_id']); ?>" alt="image" />
        <?php endforeach; ?>
    </div>
</div>
