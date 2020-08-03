<template>
  <div class="wrapper">
    <image-upload
      :show="$root.showUploader"
      :is-overlay="$root.images.length ? true : false"
      :is-multiple="isExhibitionPrints || isVinyl"
    ></image-upload>

    <image-stage
      v-for="image in $root.images"
      :key="image.id"
      :image="image"
      v-show="image.id === $root.currentImage.id"
      :is-exhibition-prints="isExhibitionPrints"
      :show-edit-tools="isExhibitionPrints || isVinyl"
    ></image-stage>

    <image-grid v-if="isExhibitionPrints || isVinyl"></image-grid>
  </div>
</template>

<script>
import ImageUpload from "./ImageUpload.vue";
import ImageStage from "./ImageStage.vue";
import ImageGrid from "./ImageGrid.vue";

export default {
  components: {
    ImageUpload,
    ImageStage,
    ImageGrid
  },
  props: {
    productType: {
      type: String,
      required: true
    }
  },
  computed: {
    isExhibitionPrints() {
      return this.productType === "Exhibition Prints" ? true : false;
    },
    isVinyl() {
      return this.productType === "Outdoor/Vinyl Prints" ? true : false;
    }
  }
};
</script>

<style lang="scss" scoped>
.wrapper {
  position: relative;
  padding: 0.5em;
}
</style>
