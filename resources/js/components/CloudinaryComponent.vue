<template>
  <div>
    <cld-context cloudName="flaunt-your-site">
      <cld-image
        publicId="WilliamBay-PartsPerMillion_DSC5995_lit9ko"
        :angle="angle"
      >
        <!-- <cld-transformation :angle="angle" /> -->
      </cld-image>
    </cld-context>

    <div>
      <button @click="rotate">ROTATE</button>
    </div>

    <div class="image-grid">
      <div v-for="(image, index) in images" :key="index">
        <img
          class="image-grid__image"
          :src="image"
          @click="currentImage(index)"
        />
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      images: this.$root.cloudinaryUrls,
      angle: 0
    }
  },
  methods: {
    currentImage(index) {
      console.log(this.images[index])
    },
    rotate() {
      // Logic to rotate at 45 degrees
      this.angle = 90
    }
  }
}
</script>

<style scoped>
.image-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  grid-gap: 1rem;
  margin-top: 2rem;
}

.image-grid__image {
  width: 100%;
}
</style>