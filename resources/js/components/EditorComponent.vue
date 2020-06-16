<template>
  <div class="wrapper">
    <image-upload
      :show="$root.showUploader"
      :is-overlay="$root.images.length ? true : false"
      :is-multiple="isExhibitionPrints"
    ></image-upload>

    <image-stage
      v-for="image in $root.images"
      :key="image.id"
      :image="image"
      v-show="image.id === $root.currentImage.id"
    ></image-stage>

    <image-grid v-if="isExhibitionPrints"></image-grid>
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
    }
  }
};
</script>

<style lang="scss" scoped>
.wrapper {
  position: relative;
}
</style>
