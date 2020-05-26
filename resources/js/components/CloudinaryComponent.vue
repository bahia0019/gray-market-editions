<template>
  <div>
    <image-stage
      v-for="image in images"
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
  },
  data() {
    return {
      images: this.$root.cloudinaryImages
      // images: this.$root.cloudinaryTestImages
    };
  },
  mounted() {
    this.infoLinks();
  },
  methods: {
    setCurrentImage(payload) {
      this.$root.currentImage = payload;
    },
    infoLinks() {
      let labels = document.querySelectorAll(".variations .label > label");
      for (let i = 0; i < labels.length; i++) {
        labels[i].innerHTML =
          labels[i].innerHTML +
          '<a href="#tab-title-' +
          labels[i].innerHTML.toLowerCase() +
          '">*</a>';

        labels[i].addEventListener("click", () => {
          let wcTabs = document.querySelectorAll(".wc-tabs li");
          for (let j = 0; j < wcTabs.length; j++) {
            wcTabs[j].classList.remove("active");
            wcTabs[j].scrollIntoView({ behavior: "smooth" });
          }
          document
            .querySelector(
              "#tab-title-" +
                labels[i].innerText.replace(/\*/g, "").toLowerCase()
            )
            .classList.add("active");
        });
      }
    }
  }
};
</script>

<style lang="scss" scoped></style>
