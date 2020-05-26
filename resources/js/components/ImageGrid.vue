<template>
  <div class="image-grid" v-if="images.length">
    <cld-image
      class="image-grid-thumb"
      v-for="(image, index) in images"
      :publicId="image.public_id"
      :key="index"
      @click.native="$root.currentImage = image"
      :class="{'active': image.public_id === $root.currentImage.public_id}"
    >
      <cld-transformation height="300" width="300" crop="fill" />
      <div class="remove-thumb" @click="removeImage(index)"></div>
    </cld-image>
    <div class="image-grid-thumb upload" @click="$root.uploadWidget">
      <svg id="icon-box-remove" viewBox="0 0 32 32">
        <path
          d="M26 2h-20l-6 6v21c0 0.552 0.448 1 1 1h30c0.552 0 1-0.448 1-1v-21l-6-6zM20 20v6h-8v-6h-6l10-8 10 8h-6zM4.828 6l2-2h18.343l2 2h-22.343z"
        />
      </svg>
      Upload More
    </div>
  </div>
  <div v-else>
    <div class="image-grid-thumb upload large" @click="$root.uploadWidget">
      <svg viewBox="0 0 32 32">
        <path
          d="M26 2h-20l-6 6v21c0 0.552 0.448 1 1 1h30c0.552 0 1-0.448 1-1v-21l-6-6zM20 20v6h-8v-6h-6l10-8 10 8h-6zM4.828 6l2-2h18.343l2 2h-22.343z"
        />
      </svg>
      <span>Upload Images</span>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      images: this.$root.cloudinaryImages
      // images: this.$root.cloudinaryTestImages
    };
  },
  methods: {
    removeImage(index) {
      this.images.splice(index, 1);
    }
  }
};
</script>

<style lang="scss" scoped>
.image-grid {
  display: grid;
  grid-template-columns: repeat(5, 1fr);
  grid-template-rows: 1fr;
  grid-gap: 0.5rem;
  margin-top: 2rem;
  padding: 0.5em;
  border: 1px solid #666;
}

.image-grid button {
}

.image-grid-thumb {
  display: grid;
  cursor: pointer;
  transition: 250ms;
  border: none;
  position: relative;
}

.upload {
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
  font-size: 75%;
  color: #666;
  fill: #666;
  border: 1px solid #666;
  filter: opacity(0.5);
}
.image-grid-thumb.upload:hover {
  filter: opacity(1);
}

.upload svg {
  width: 20px;
  height: 20px;
}

.image-grid-thumb.large {
  min-height: 500px;
}

.image-grid-thumb.large svg {
  width: 50px;
  height: 50px;
}

.image-grid-thumb.large span {
  font-size: 16px;
  margin-top: 4px;
}

.image-grid-thumb img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  grid-area: 1 / 1 / 2 / 2;
}

.image-grid-thumb:hover,
.image-grid-thumb.active {
  filter: opacity(0.5);
}

.image-grid-thumb:hover .remove-thumb {
  display: block;
}

.remove-thumb {
  display: none;
  width: 12px;
  height: 12px;
  background-color: red;
  border-radius: 20px;
  position: absolute;
  right: -4px;
  top: -4px;
  filter: opacity(0.75);
}
.remove-thumb:hover {
  filter: opacity(1);
}
</style>