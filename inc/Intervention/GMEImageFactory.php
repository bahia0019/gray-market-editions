<?php

namespace BoxyBird\GME\Intervention;

use WP_Error;
use Intervention\Image\ImageManager;

class GMEImageFactory
{
    public $args;

    public $image;

    public $processed_image_url;

    public $processed_image_path;

    protected $manager;

    protected $attachment_id;

    public function __construct(int $attachment_id)
    {
        $this->manager       = new ImageManager;
        $this->attachment_id = $attachment_id;

        $this->prepareArgs();
        $this->setImage();
    }

    public function process()
    {
        if (!empty($this->errors)) {
            return new WP_Error('Missing args', ['status' => 422], $this->errors);
        }

        $this->setPhpEnv();

        $this->mutateImage();
    }

    protected function mutateImage(): void
    {
        $canvas_width   = $this->args['canvas_width']; // fin: canvasWidth
        $canvas_height  = $this->args['canvas_height']; // fin: canvasHeight
        $overlay_width  = $this->args['overlay_width']; // fin: overlayWidth
        $overlay_height = $this->args['overlay_height']; // fin: overlayWidth
        $angle          = $this->args['angle']; // fin: angle
        $border         = $this->args['border']; // fin: border

        $canvas = $this->manager
            ->canvas($canvas_width, $canvas_height, '#ffffff');

        $overlay = $this->image
            ->orientate()
            ->resize($overlay_width, $overlay_height)
            ->rotate(-$angle, '#ffffff'); // Invert rotates counter-clockwise appending (-)

        $file_name = $this->attachment_id . '_' . $overlay->filename . '.' . $this->image->extension;
        update_post_meta($this->attachment_id, 'gme_image_file_name', $file_name);

        $final_image = $canvas
            ->insert($overlay, 'center')
            ->resizeCanvas($border, $border, 'center', true, '#ffffff')
            ->resize($canvas_width, $canvas_height)
            ->save(GME_UPLOADS_PATH . $file_name, 100, 'jpg');

        $this->processed_image_url  = GME_UPLOADS_URL . $file_name;
        $this->processed_image_path = GME_UPLOADS_PATH . $file_name;
    }

    protected function setImage(): void
    {
        $this->image = $this->manager->make($this->args['image_path']);
    }

    protected function prepareArgs(): void
    {
        $args = [];

        // Image path
        ($wp_image = wp_get_original_image_path($this->attachment_id))
            ? $args['image_path']            = $wp_image
            : $this->errors['missing_args']  = ['image_path'];

        $trans = get_post_meta($this->attachment_id, 'gme_image_transformations', true);

        $args['canvas_width']   = (float) $trans['canvasWidth'];
        $args['canvas_height']  = (float) $trans['canvasHeight'];
        $args['overlay_width']  = (float) $trans['overlayWidth'];
        $args['overlay_height'] = (float) $trans['overlayHeight'];
        $args['angle']          = (float) $trans['angle'];
        $args['border']         = (float) $trans['border'];

        $this->args = $args;
    }

    protected function setPhpEnv(): void
    {
        $memory    = defined('GME_MEMORY_LIMIT') ? GME_MEMORY_LIMIT : '1024M';
        $execution = defined('GME_EXECUTION_TIME') ? GME_EXECUTION_TIME : '90';

        ini_set('memory_limit', $memory);
        ini_set('max_execution_time', $execution);
    }
}
