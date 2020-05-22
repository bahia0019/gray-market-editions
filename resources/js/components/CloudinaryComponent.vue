<template>
  <div>
    <div ref="stage" class="stage" :class="{ 'processing-image': $root.processingImage}">
      <cld-image publicId="16_20_bg_l0wg9w.jpg" ref="cldImage" onload="cloudinaryOnLoad()">
        <cld-transformation :width="canvasWidth" :height="canvasHeight" crop="scale" />
        <cld-transformation
          :overlay="currentImage"
          :width="imageWidth"
          :height="imageHeight"
          crop="scale"
          :angle="angle"
        />
      </cld-image>

      <svg viewBox="0 0 32 32" class="spinner">
        <path
          d="M32 12h-12l4.485-4.485c-2.267-2.266-5.28-3.515-8.485-3.515s-6.219 1.248-8.485 3.515c-2.266 2.267-3.515 5.28-3.515 8.485s1.248 6.219 3.515 8.485c2.267 2.266 5.28 3.515 8.485 3.515s6.219-1.248 8.485-3.515c0.189-0.189 0.371-0.384 0.546-0.583l3.010 2.634c-2.933 3.349-7.239 5.464-12.041 5.464-8.837 0-16-7.163-16-16s7.163-16 16-16c4.418 0 8.418 1.791 11.313 4.687l4.687-4.687v12z"
        />
      </svg>
    </div>
    <div class="edit-tools" :class="{ processingImage: $root.processingImage}">
      <button id="full-frame" @click="setCanvasWidth(); setCanvasHeight();">
        <svg viewBox="0 0 32 32">
          <path d="M32 0h-13l5 5-6 6 3 3 6-6 5 5z" />
          <path d="M32 32v-13l-5 5-6-6-3 3 6 6-5 5z" />
          <path d="M0 32h13l-5-5 6-6-3-3-6 6-5-5z" />
          <path d="M0 0v13l5-5 6 6 3-3-6-6 5-5z" />
        </svg>
        <div class="tool-tip">
          <div class="triangle"></div>
          <div class="tip">Full Frame</div>
        </div>
      </button>

      <button id="borders">
        <svg viewBox="0 0 32 32">
          <path
            d="M28 0h-24c-2.2 0-4 1.8-4 4v24c0 2.2 1.8 4 4 4h24c2.2 0 4-1.8 4-4v-24c0-2.2-1.8-4-4-4zM28 28h-24v-24h24v24z"
          />
        </svg>
        <div class="tool-tip">
          <div class="triangle"></div>
          <div class="tip">Borders</div>
        </div>
      </button>

      <button id="orientation" @click="setOrientation">
        <svg viewBox="0 0 32 32">
          <path d="M26 28h-20v-4l6-10 8.219 10 5.781-4v8z" />
          <path d="M26 15c0 1.657-1.343 3-3 3s-3-1.343-3-3 1.343-3 3-3c1.657 0 3 1.343 3 3z" />
          <path
            d="M28.681 7.159c-0.694-0.947-1.662-2.053-2.724-3.116s-2.169-2.030-3.116-2.724c-1.612-1.182-2.393-1.319-2.841-1.319h-15.5c-1.378 0-2.5 1.121-2.5 2.5v27c0 1.378 1.122 2.5 2.5 2.5h23c1.378 0 2.5-1.122 2.5-2.5v-19.5c0-0.448-0.137-1.23-1.319-2.841zM24.543 5.457c0.959 0.959 1.712 1.825 2.268 2.543h-4.811v-4.811c0.718 0.556 1.584 1.309 2.543 2.268zM28 29.5c0 0.271-0.229 0.5-0.5 0.5h-23c-0.271 0-0.5-0.229-0.5-0.5v-27c0-0.271 0.229-0.5 0.5-0.5 0 0 15.499-0 15.5 0v7c0 0.552 0.448 1 1 1h7v19.5z"
          />
        </svg>
        <div class="tool-tip">
          <div class="triangle"></div>
          <div class="tip">Switch to Portrait layout</div>
        </div>
      </button>

      <button id="rotate" @click="setAngle">
        <svg viewBox="0 0 32 32">
          <path
            d="M32 12h-12l4.485-4.485c-2.267-2.266-5.28-3.515-8.485-3.515s-6.219 1.248-8.485 3.515c-2.266 2.267-3.515 5.28-3.515 8.485s1.248 6.219 3.515 8.485c2.267 2.266 5.28 3.515 8.485 3.515s6.219-1.248 8.485-3.515c0.189-0.189 0.371-0.384 0.546-0.583l3.010 2.634c-2.933 3.349-7.239 5.464-12.041 5.464-8.837 0-16-7.163-16-16s7.163-16 16-16c4.418 0 8.418 1.791 11.313 4.687l4.687-4.687v12z"
          />
        </svg>
        <div class="tool-tip">
          <div class="triangle"></div>
          <div class="tip">Rotate Image</div>
        </div>
      </button>
    </div>

    <!-- Begin thumbnail grid. -->
    <div class="image-grid">
      <cld-image
        class="image-grid-thumb"
        v-for="(image, index) in images"
        :publicId="image.public_id"
        :key="index"
        @click.native="setCurrentImage(index)"
        :class="{'active': image.public_id === currentImage}"
      >
        <cld-transformation height="300" width="300" crop="fill" />
        <div class="remove-thumb" @click="removeImage(index)"></div>
      </cld-image>
      <div class="image-grid-thumb upload" @click="addAdditionalImages">
        <svg id="icon-box-remove" viewBox="0 0 32 32">
          <path
            d="M26 2h-20l-6 6v21c0 0.552 0.448 1 1 1h30c0.552 0 1-0.448 1-1v-21l-6-6zM20 20v6h-8v-6h-6l10-8 10 8h-6zM4.828 6l2-2h18.343l2 2h-22.343z"
          />
        </svg>
        Upload More
      </div>
    </div>
    <!-- End thumbnail grid. -->
  </div>
</template>

<script>
export default {
  data() {
    return {
      angle: 0,
      images: this.$root.cloudinaryImages,
      currentImage: "",
      canvasWidth: 1000,
      canvasHeight: 800,
      imageWidth: 750,
      imageHeight: 500
    };
  },
  mounted() {
    this.watchCldImage();
    this.getStageHeight();
    this.infoLinks();
  },
  methods: {
    getStageHeight() {
      let stage = this.$refs.stage;
      let stageHeight = stage.offsetHeight;
    },
    // getSizeInfo() {
    //   let size = this.$root.variations;
    //   console.log(size);
    //   //   size.addEventListener("change", event => {
    //   //     let widthHeight = size.selectedOptions[0].value.split("x");

    //   //     let height = parseInt(widthHeight[0]);
    //   //     let width = parseInt(widthHeight[1]);
    //   //     console.log(height + "X" + width);
    //   //   });
    // },
    // Set and Reflect Current Image.
    setCurrentImage(index) {
      this.$root.processingImage = true;
      this.currentImage = this.images[index].public_id;
      this.reflectCurrentImage();
    },
    addAdditionalImages() {
      console.log("Select additional images, and add to existing array.");
    },
    removeImage(index) {
      this.images.splice(index, 1);
    },
    /**
     * Canvas methods
     */
    //   Set and Reflect Canvas Width
    setCanvasWidth() {
      this.$root.processingImage = true;
      this.reflectCanvasWidth();
    },
    reflectCanvasWidth() {
      let obj = this.$refs.cldImage.transformations.find(
        transformation => "width" in transformation
      );
      this.$set(obj, "width", this.canvasWidth);
    },
    //   Set and Reflect Canvas Height
    setCanvasHeight() {
      this.$root.processingImage = true;
      this.reflectCanvasHeight();
    },
    reflectCanvasHeight() {
      let obj = this.$refs.cldImage.transformations.find(
        transformation => "height" in transformation
      );
      this.$set(obj, "height", this.canvasHeight);
    },
    setOrientation() {
      this.$root.processingImage = true;
      this.canvasWidth = this.canvasHeight * 0.8;
      this.reflectOrientation();
    },
    reflectOrientation() {
      let obj = this.$refs.cldImage.transformations.find(
        transformation => "width" in transformation
      );
      this.$set(obj, "width", this.canvasWidth);
    },

    /**
     * Image methods
     */

    reflectCurrentImage() {
      let obj = this.$refs.cldImage.transformations.find(
        transformation => "overlay" in transformation
      );
      this.$set(obj, "overlay", this.currentImage.replace("/", ":"));
    },
    //   Set and Reflect Image Width
    setImageWidth() {
      this.$root.processingImage = true;
      this.imageWidth = canvasWidth;
      this.reflectImageWidth();
    },
    reflectImageWidth() {
      let obj = this.$refs.cldImage.transformations.find(
        transformation => "width" in transformation
      );
      this.$set(obj, "width", this.imageWidth);
    },
    //   Set and Reflect Image Height
    setImageHeight() {
      this.$root.processingImage = true;
      this.imageHeight = canvasHeight;
      this.reflectImageHeight();
    },
    reflectImageHeight() {
      let obj = this.$refs.cldImage.transformations.find(
        transformation => "height" in transformation
      );
      this.$set(obj, "height", this.imageHeight);
    },
    //   Set and Reflect Angle
    setAngle() {
      this.$root.processingImage = true;
      this.angle = this.angle === 270 ? 0 : this.angle + 90;
      this.reflectAngle();
    },
    reflectAngle() {
      let obj = this.$refs.cldImage.transformations.find(
        transformation => "angle" in transformation
      );
      this.$set(obj, "angle", this.angle);
    },
    watchCldImage() {
      this.$watch("$refs.cldImage.imageAttrs.src", {
        handler(newUrl, oldUrl) {},
        immediate: true
      });
    },
    infoLinks() {
      let size = document.querySelector("[for=" + "size" + "]");
      size.innerHTML = size.innerHTML + "<a href='#'>*</a>";
    }
  },
  computed: {
    getSizeInfo() {
      return this.$root.variations[0].value;
    }
  }
};
</script>

<style scoped>
.stage {
  display: flex;
  justify-content: center;
  align-items: center;
}

@keyframes spinner {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}

.spinner {
  width: 25px;
  position: absolute;
  z-index: 10;
  animation-name: spinner;
  animation-duration: 1s;
  animation-iteration-count: infinite;
  animation-timing-function: linear;
}

.stage .spinner {
  display: none;
}
.stage.processing-image {
  filter: opacity(0.5);
}

.stage.processing-image .spinner {
  display: block;
}

.cld-image {
  border: 1px solid #999;
}

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

.edit-tools {
  display: flex;
  justify-content: flex-end;
}

.edit-tools button {
  width: 35px;
  height: 35px;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 0;
  margin: 0;
  position: relative;
  transition: 350ms;
}

.edit-tools.processingImage button {
  filter: opacity(0.5);
  cursor: wait;
}

.edit-tools svg {
  width: 17px;
}

/* Tooltips */
.tool-tip {
  visibility: hidden;
  width: fit-content;
  top: 34px;
  position: absolute;
  right: -30px;
}

.edit-tools button:hover .tool-tip {
  visibility: visible;
}

.triangle {
  width: 5px;
  height: 5px;
  border-left: 15px solid transparent;
  border-right: 15px solid transparent;
  border-bottom: 10px solid #57b8ff;
  position: relative;
  left: 60%;
}

.tip {
  color: #fff;
  background-color: #57b8ff;
  padding: 0.5em 1.5em;
  border-radius: 0.6em;
  width: fit-content;
  white-space: nowrap;
}
</style>