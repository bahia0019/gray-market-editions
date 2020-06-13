<div id="gme-image-mini-grid" v-cloak>
    <div class="gme-image-mini-grid__grid">
        <?php foreach ($gme_image_data as $data): ?>
            <img class="gme-image-mini-grid__image" src="<?php echo wp_get_attachment_image_url($data['attachment_id']); ?>" alt="image" />
        <?php endforeach; ?>
    </div>
    <button class="button gme-image-mini-grid__button" @click="process" :disabled="isProcessing">
        {{ isProcessing ? 'Processing' : 'Process' }}
    </button>
    <div class="gme-image-mini-grid__downloads" v-if="zip">
        <a :href="zip" download>Download All</a>
    </div>
    <hr>
    <div class="gme-image-mini-grid__downloads" v-for="url in processedUrls">
        <a :href="url" download>Download</a>
    </div>
</div>

<script>
    const data = {
        action: 'gme_ajax_image_process',
        zip_file_name: '<?php echo $zip_file_name; ?>',
        nonce: '<?php echo wp_create_nonce('gme_ajax_nonce'); ?>',
        gme_image_data: '<?php echo json_encode($gme_image_data); ?>'
    }

    document.addEventListener('DOMContentLoaded', function () {
        const app = new GME_VUE({
            el: '#gme-image-mini-grid',
            data() {
                return {
                    zip: '',
                    processedUrls: [],
                    isProcessing: false
                }
            },
            methods: {
                async process() {
                    this.isProcessing = true

                    let res = await jQuery.post(ajaxurl, data)
                    
                    this.zip = res.zip
                    this.processedUrls = res.images
                    this.isProcessing = false
                }
            }
        })  
    })
</script>