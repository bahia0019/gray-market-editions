<template>
  <div>
    <div v-show="currentImage.public_id">
      <div
        ref="stage"
        class="stage"
        :class="{ 'processing-image': $root.processingImage, 'portrait': portrait}"
      >
        <div ref="frame" class="frame"></div>
        <cld-image publicId="16_20_bg_l0wg9w.jpg" ref="cldImage" onload="cloudinaryOnLoad()">
          <cld-transformation :width="canvasWidth" :height="canvasHeight" crop="scale" />
          <cld-transformation
            :overlay="currentImage.public_id"
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

      <edit-tools
        @angleClick="setAngle"
        @orientationClick
        @fullFrameClick="
                    setCanvasWidth()
                    setCanvasHeight()
                "
      ></edit-tools>
    </div>

    <image-grid @selectImage="setCurrentImage"></image-grid>

    <poster-templates v-if="productType === 'Posters'"></poster-templates>
  </div>
</template>

<script>
import EditTools from "./EditTools.vue";
import ImageGrid from "./ImageGrid.vue";
import PosterTemplates from "./PosterTemplates.vue";

export default {
  components: {
    EditTools,
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
      angle: 0,
      currentImage: "",
      canvasWidth: 1000,
      canvasHeight: 800,
      imageWidth: "",
      imageHeight: "",
      portrait: false,
      stageHeight: 400
    };
  },
  mounted() {
    this.watchCldImage();
    this.getStageHeight();
    this.infoLinks();
    this.setCanvasHeight();
    this.getFrameInfo();
  },
  methods: {
    getStageHeight() {
      let stage = this.$refs.stage;
      this.stageHeight = 400;
    },

    getFrameInfo() {
      let frame = document.querySelector("select#frame");
      let regex = /[!"#$%&'()*+,./:;<=>?@[\]^_`{|}~]/g;
      let frameStyle;
      frame.addEventListener("change", event => {
        frameStyle = frame.selectedOptions[0].value
          .toLowerCase()
          .replace(regex, "")
          .replace(/ +/g, "-");

        switch (frameStyle) {
          case "black":
            this.$refs.frame.classList = "frame";
            this.$refs.frame.classList.add("frame-black", "active");
            break;
          case "down-n-dirty":
            this.$refs.frame.classList = "frame";
            this.$refs.frame.classList.add("frame-down-n-dirty", "active");
            break;
          default:
            this.$refs.frame.classList = "frame";
        }
      });
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
    // Takes the Print Size info from the Product Size select dropdown.
    setCanvasHeight() {
      let size = document.querySelector("select#size");
      size.addEventListener("change", event => {
        this.$root.processingImage = true;
        let widthHeight = size.selectedOptions[0].value.split("x");
        let canvasWidth = parseInt(widthHeight[1]);
        let canvasMultiplier = 1000 / canvasWidth;
        this.canvasHeight = Math.round(
          parseInt(widthHeight[0]) * canvasMultiplier
        );
        this.reflectCanvasHeight();
      });
    },
    reflectCanvasHeight() {
      let obj = this.$refs.cldImage.transformations.find(
        transformation => "height" in transformation
      );
      this.$set(obj, "height", this.canvasHeight);
    },

    /**
     * Image methods
     */

    // Set and Reflect Current Image.
    setCurrentImage(payload) {
      this.$root.processingImage = true;
      this.currentImage = payload;
      this.reflectCurrentImage();
      this.getImageOrientation();
      this.setCanvasHeight();
      this.setImageWidth();
      this.setImageHeight();
    },
    reflectCurrentImage() {
      let obj = this.$refs.cldImage.transformations.find(
        transformation => "overlay" in transformation
      );
      this.$set(obj, "overlay", this.currentImage.public_id.replace("/", ":"));
    },
    getImageOrientation() {
      if (this.currentImage.width > this.currentImage.height) {
        this.portrait = false;
      } else {
        this.portrait = true;
      }
    },
    //   Set and Reflect Image Width
    setImageWidth() {
      this.$root.processingImage = true;
      this.imageWidth = this.canvasWidth;
      //   this.imageWidth = this.currentImage.width;
      this.reflectImageWidth();
    },
    reflectImageWidth() {
      let obj = this.$refs.cldImage.transformations.find(
        transformation => "overlay" in transformation
      );
      this.$set(obj, "width", this.imageWidth);
    },
    //   Set and Reflect Image Height
    setImageHeight() {
      this.$root.processingImage = true;
      this.imageHeight = this.canvasHeight;
      //   this.imageHeight = this.currentImage.height;

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
        handler(newUrl, oldUrl) {
          let existing = this.$root.finalImages.filter(
            image => this.currentImage === image.public_id
          );

          if (existing.length) {
            this.$set(existing[0], "url", newUrl);
          } else {
            this.$root.finalImages.push({
              public_id: this.currentImage.public_id,
              url: newUrl || oldUrl
            });
          }
        },
        immediate: false
      });
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

<style lang="scss" scoped>
.stage {
  display: flex;
  justify-content: center;
  align-items: center;
  position: relative;
  overflow: hidden;
}
.stage.portrait {
  height: 400px;
}

.frame {
  display: none;
  position: absolute;
  border: 30px solid transparent;
  width: 100%;
  height: 100%;
}
.frame-black {
  border-color: #000;
}
.frame-down-n-dirty {
  border-image: url(https://img.freepik.com/free-photo/wooden-textured-background_53876-14865.jpg?size=626&ext=jpg)
    50 round;
}
.frame.active {
  display: block;
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
</style>
