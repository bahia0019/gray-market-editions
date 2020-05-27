<template>
  <div>
    <image-stage
      v-for="image in $root.cloudinaryImages"
      :key="image.public_id"
      :image="image"
      v-show="image.public_id === $root.currentImage.public_id"
    ></image-stage>

    <image-grid></image-grid>

    <poster-templates v-if="productType === 'Posters'"></poster-templates>
  </div>
</template>

<script>
import ImageStage from "./ImageStage.vue";
import ImageGrid from "./ImageGrid.vue";
import PosterTemplates from "./PosterTemplates.vue";

export default {
  components: {
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
