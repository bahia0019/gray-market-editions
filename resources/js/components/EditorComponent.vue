<template>
  <div>
    <image-stage
      v-for="image in $root.images"
      :key="image.id"
      :image="image"
      v-show="image.id === $root.currentImage.id"
    ></image-stage>

    <image-upload></image-upload>

    <image-grid></image-grid>

    <poster-templates v-if="productType === 'Posters'"></poster-templates>
  </div>
</template>

<script>
import ImageUpload from "./ImageUpload.vue";
import ImageStage from "./ImageStage.vue";
import ImageGrid from "./ImageGrid.vue";
import PosterTemplates from "./PosterTemplates.vue";

export default {
  components: {
    ImageUpload,
    ImageStage,
    ImageGrid,
    PosterTemplates
  },
  props: {
    productType: {
      type: String,
      required: true
    }
  }
};
</script>

<style lang="scss" scoped></style>
