<template>
  <div class="image-grid" v-if="$root.images.length">
    <div
      class="image-grid-thumb"
      v-for="image in $root.images"
      :key="image.id"
      @click="$root.currentImage = image"
      :class="{'active': image === $root.currentImage}"
    >
      <img :src="image.thumbnail.url" />
      <div class="remove-thumb" @click="removeImage(image.id)"></div>
    </div>
    <div class="image-grid-thumb upload" @click="$root.uploadWidget">
      <svg id="icon-box-remove" viewBox="0 0 32 32">
        <path
          d="M26 2h-20l-6 6v21c0 0.552 0.448 1 1 1h30c0.552 0 1-0.448 1-1v-21l-6-6zM20 20v6h-8v-6h-6l10-8 10 8h-6zM4.828 6l2-2h18.343l2 2h-22.343z"
        />
      </svg>
      Upload More
    </div>
  </div>
</template>

<script>
export default {
  methods: {
    removeImage(id) {
      this.$root.images = this.$root.images.filter(image => image.id !== id);
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
  height: 100px;
  object-fit: cover;
  grid-area: 1 / 1 / 2 / 2;
}

.image-grid-thumb:hover img,
.image-grid-thumb.active img {
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