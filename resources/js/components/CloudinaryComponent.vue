<template>
  <div>
    <cld-context cloudName="flaunt-your-site">
      <cld-image publicId="WilliamBay-PartsPerMillion_DSC5995_lit9ko" :angle="angle" ref="cldImage">
        <cld-transformation :angle="angle" />
      </cld-image>
    </cld-context>

    <div>
      <button @click="rotate">ROTATE</button>
    </div>

    <cld-context class="image-grid" cloudName="flaunt-your-site">
      <cld-image
        class="image-grid-thumb"
        v-for="(image, index) in images"
        :key="index"
        :publicId="image"
      >
        <cld-transformation height="300" width="300" crop="fill" />
      </cld-image>
    </cld-context>
  </div>
</template>

<script>
export default {
  data() {
    return {
      angle: 0,
      currentImage: "",
      images: this.$root.cloudinaryUrls
    };
  },
  mounted() {
    this.watchCldImage();
  },
  methods: {
    rotate() {
      this.angle = this.angle === 270 ? 0 : this.angle + 90;
    },
    setCurrentImage(index) {
      this.currentImage = this.images[index];
    },
    watchCldImage() {
      this.$watch("$refs.cldImage.imageAttrs.src", {
        handler(newUrl, oldUrl) {
          console.log(oldUrl);
          console.log(newUrl);
        },
        immediate: true
      });
    }
  }
};
</script>

<style scoped>
.image-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  grid-template-rows: 1fr;
  grid-gap: 0.5rem;
  margin-top: 2rem;
  padding: 0.5em;
  border: 1px solid #666;
}

.image-grid-thumb {
  background: black;
  display: grid;
}

.image-grid-thumb img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  grid-area: 1 / 1 / 2 / 2;
  border-radius: 0;
}
</style>