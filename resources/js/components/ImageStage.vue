<template>
  <div>
    <div
      ref="stage"
      class="stage"
      :style="{ 'max-height': stageHeight + 'px' }"
      :class="{ 'processing-image': $root.processingImage, 'portrait': portrait }"
    >
      <div ref="frame" class="frame"></div>
      <cld-image publicId="assets/16_20_bg_khaqct.jpg" ref="cldImage" onload="cloudinaryOnLoad()">
        <!-- Canvas Transformations -->
        <cld-transformation
          v-if="portrait"
          :width="portraitCanvasShortDimension"
          :height="portraitCanvasLongDimension"
          crop="scale"
        />
        <cld-transformation
          v-else
          :width="canvasLongDimension"
          :height="canvasShortDimension"
          crop="scale"
        />
        <!-- Overlay/Image Transformations -->
        <cld-transformation
          v-if="portrait"
          :overlay="image.public_id"
          :width="imageShortDimension"
          :height="imageLongDimension"
          :angle="angle"
          :crop="imageCrop"
        />
        <cld-transformation
          v-else
          :overlay="image.public_id"
          :width="imageLongDimension"
          :height="imageShortDimension"
          :angle="angle"
          :crop="imageCrop"
        />
      </cld-image>

      <svg viewBox="0 0 32 32" class="spinner">
        <path
          d="M32 12h-12l4.485-4.485c-2.267-2.266-5.28-3.515-8.485-3.515s-6.219 1.248-8.485 3.515c-2.266 2.267-3.515 5.28-3.515 8.485s1.248 6.219 3.515 8.485c2.267 2.266 5.28 3.515 8.485 3.515s6.219-1.248 8.485-3.515c0.189-0.189 0.371-0.384 0.546-0.583l3.010 2.634c-2.933 3.349-7.239 5.464-12.041 5.464-8.837 0-16-7.163-16-16s7.163-16 16-16c4.418 0 8.418 1.791 11.313 4.687l4.687-4.687v12z"
        />
      </svg>
    </div>

    <div class="tray">
      <div class="notices" :style="{'color':imageDPIColor}">Your image is {{imageDPI}} dpi</div>
      <edit-tools :portrait="portrait" @angleClick="setAngle" @fullFrameClick="setFullFrame"></edit-tools>
    </div>
  </div>
</template>

<script>
import EditTools from "./EditTools.vue";

export default {
  components: {
    EditTools
  },
  props: {
    image: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      stageHeight: "",
      canvasLongDimension: "",
      canvasShortDimension: "",
      portraitCanvasLongDimension: "",
      portraitCanvasShortDimension: "",
      imageLongDimension: "",
      imageShortDimension: "",
      imageCrop: "",
      imageProportion: "",
      imageDPI: "",
      imageDPIColor: "",
      portrait: false,
      angle: 0
    };
  },
  mounted() {
    this.watchCldImage();
    this.getCanvasInfo();
    this.getImageInfo();
    this.reflectOrientation();
    this.getFrameInfo();
    this.reflectCurrentImage();
    // this.getStageHeight();
  },
  updated() {
    size.addEventListener("change", event => {
      this.$root.processingImage = true;
      this.getCanvasInfo();
      this.getImageInfo();
      this.reflectOrientation();
    });
    this.dpiCheck();
    this.reflectOrientation();
  },
  methods: {
    reflectCurrentImage() {
      let obj = this.$refs.cldImage.transformations.find(
        transformation => "overlay" in transformation
      );
      this.$set(obj, "overlay", this.image.public_id.replace("/", ":"));
    },
    /**
     * Canvas methods
     */
    // Takes the Print Size info from the Product Size select dropdown.
    getCanvasInfo() {
      this.$root.processingImage = true;
      this.size = document.querySelector("select#size");
      this.widthHeight = size.selectedOptions[0].value.split("x");
      this.longInch = parseInt(this.widthHeight[1]);
      this.canvasLongDimension = 1000;
      this.canvasMultiplier = 1000 / parseInt(this.widthHeight[1]);
      this.canvasShortDimension = Math.round(
        parseInt(this.widthHeight[0]) * this.canvasMultiplier
      );
      this.canvasProportion =
        this.canvasShortDimension / this.canvasLongDimension;

      //   this.reflectcanvasLongDimension();
      //   this.reflectcanvasShortDimension();
    },
    // reflectcanvasLongDimension() {
    //   let obj = this.$refs.cldImage.transformations.find(
    //     transformation => "width" in transformation
    //   );
    //   this.$set(obj, "width", this.canvasLongDimension);
    // },
    // reflectcanvasShortDimension() {
    //   let obj = this.$refs.cldImage.transformations.find(
    //     transformation => "height" in transformation
    //   );
    //   this.$set(obj, "height", this.canvasShortDimension);
    // },
    // getStageHeight() {
    //   const myObserver = new ResizeObserver(this.callback);
    //   myObserver.observe(this.$refs.stage);
    // },
    // callback() {
    //   this.stageHeight = this.$refs.stage.offsetWidth * this.canvasProportion;
    // },

    /**
     * Image methods
     */

    getImageInfo() {
      // If LANDSCAPE.
      if (this.image.width > this.image.height) {
        this.portrait = false;
        // this.imageProportion = this.image.height / this.image.width;
        // this.imageLongDimension = this.canvasLongDimension;
        // this.imageShortDimension = Math.round(
        //   this.canvasLongDimension * this.imageProportion
        // );
        this.dpiCheck();
        // If PORTRAIT.
      } else {
        this.portrait = true;
        // this.imageProportion = this.image.width / this.image.height;
        // this.imageShortDimension = this.canvasLongDimension;
        // this.imageLongDimension = Math.round(
        //   this.imageShortDimension * this.imageProportion
        // );
      }
      //   this.reflectimageLongDimension();
      //   this.reflectimageShortDimension();
    },
    // reflectimageLongDimension() {
    //   let obj = this.$refs.cldImage.transformations.find(
    //     transformation => "overlay" in transformation
    //   );
    //   this.$set(obj, "width", this.imageLongDimension);
    // },
    // reflectimageShortDimension() {
    //   let obj = this.$refs.cldImage.transformations.find(
    //     transformation => "overlay" in transformation
    //   );
    //   this.$set(obj, "height", this.imageShortDimension);
    // },

    // setOrientation() {
    //   if (true === this.portrait) {
    //     console.log(
    //       (this.canvasShortDimension = this.canvasShortDimension / 2)
    //     );
    //     console.log((this.canvasLongDimension = this.canvasLongDimension / 2));
    //   }
    //   reflectOrientation();
    // },
    reflectOrientation() {
      if (true === this.portrait) {
        // Portrait.
        this.portraitCanvasLongDimension = this.canvasLongDimension / 2;
        this.portraitCanvasShortDimension = this.canvasShortDimension / 2;
        this.imageProportion = this.image.width / this.image.height;
        this.imageLongDimension = 500;
        this.imageShortDimension = Math.floor(
          this.imageLongDimension * this.imageProportion
        );
        // debugger;
        console.log(this.imageShortDimension);

        let obj = this.$refs.cldImage.transformations.find(
          transformation => "width" in transformation
        );
        this.$set(obj, "width", this.portraitCanvasShortDimension);
        this.$set(obj, "height", this.portraitCanvasLongDimension);

        let obj2 = this.$refs.cldImage.transformations.find(
          transformation => "overlay" in transformation
        );
        this.$set(obj2, "width", this.imageShortDimension);
        this.$set(obj2, "height", this.imageLongDimension);
      } else {
        //   Landscape
        this.imageProportion = this.image.height / this.image.width;
        this.imageLongDimension = this.canvasLongDimension;
        this.imageShortDimension = Math.floor(
          this.canvasLongDimension * this.imageProportion
        );
        // set Canvas & Image to Landscape.
        let obj = this.$refs.cldImage.transformations.find(
          transformation => "width" in transformation
        );
        this.$set(obj, "height", this.canvasShortDimension);
        this.$set(obj, "width", this.canvasLongDimension);

        let obj2 = this.$refs.cldImage.transformations.find(
          transformation => "overlay" in transformation
        );
        this.$set(obj2, "width", this.imageLongDimension);
        this.$set(obj2, "height", this.imageShortDimension);
      }
    },
    setFullFrame() {
      this.$root.processingImage = true;

      this.imageLongDimension = this.canvasLongDimension;
      this.imageShortDimension = this.canvasShortDimension;
      this.imageCrop = "fill";
      this.reflectFullFrame();
    },
    reflectFullFrame() {
      let obj2 = this.$refs.cldImage.transformations.find(
        transformation => "overlay" in transformation
      );
      this.$set(obj2, "height", this.imageShortDimension);
      this.$set(obj2, "width", this.imageLongDimension);
      this.$set(obj2, "crop", this.imageCrop);
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
    dpiCheck() {
      this.imageDPI = Math.round(this.image.width / this.longInch);
      if (200 < this.imageDPI) {
        this.imageDPIColor = "green";
      } else {
        this.imageDPIColor = "red";
      }
    },
    watchCldImage() {
      this.$watch("$refs.cldImage.imageAttrs.src", {
        handler(newUrl, oldUrl) {
          this.image.gme_final_url = newUrl;
        },
        immediate: false
      });
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

.tray {
  display: flex;
  justify-content: space-between;
  align-items: center;
}
</style>
