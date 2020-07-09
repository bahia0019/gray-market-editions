<template>
  <div>
    <div
      class="stage"
      :class="{
                'processing-image': $root.processingImage,
            }"
    >
      <div ref="frame" class="frame"></div>
      <canvas
        ref="canvas"
        :width="this.canvasWidth"
        :height="this.canvasHeight"
        :style="canvasStyles"
      >
        <img
          ref="currentimage"
          :width="imageWidth"
          :height="imageHeight"
          :src="image.editor_image.url"
        />
      </canvas>
      <svg viewBox="0 0 32 32" class="spinner">
        <path
          d="M32 12h-12l4.485-4.485c-2.267-2.266-5.28-3.515-8.485-3.515s-6.219 1.248-8.485 3.515c-2.266 2.267-3.515 5.28-3.515 8.485s1.248 6.219 3.515 8.485c2.267 2.266 5.28 3.515 8.485 3.515s6.219-1.248 8.485-3.515c0.189-0.189 0.371-0.384 0.546-0.583l3.010 2.634c-2.933 3.349-7.239 5.464-12.041 5.464-8.837 0-16-7.163-16-16s7.163-16 16-16c4.418 0 8.418 1.791 11.313 4.687l4.687-4.687v12z"
        />
      </svg>
      <div v-if="dpiWarning" :class="{ warning: dpiWarning }">
        <div>
          <p>
            At {{fin.canvasHeight / 200}}"x{{fin.canvasWidth / 200}}", your image resolution is {{ imageDPI }} pixels per inch. For optimal image quality, we recommend a resolution of 200 ppi or more.
            <br />Files that are significantly lower in resolution may appear pixelated or blurry in the final print. We suggest either selecting a smaller print size or uploading a larger file.
          </p>
          <button
            v-on:click="dpiWarning = false"
          >If you wish to continue with your existing file, click here.</button>
        </div>
      </div>
    </div>

    <div class="tray">
      <div>Your Chosen Print size is {{fin.canvasHeight / 200}}"x{{fin.canvasWidth / 200}}". Actual Image Size is {{(fin.overlayHeight / 200 ).toFixed(1)}}"x{{(fin.overlayWidth / 200).toFixed(1)}}"</div>
      <edit-tools
        v-if="isExhibitionPrints"
        :fullFrame="fullFrame"
        @fullFrameClick="setFullFrame"
        :canvasPortrait="canvasPortrait"
        @toggleCanvasOrientation="toggleCanvasOrientation"
        @angleClick="rotate"
      ></edit-tools>
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
    },
    isExhibitionPrints: {
      type: Boolean,
      required: true
    }
  },
  data() {
    return {
      canvasLongInches: "",
      canvasShortInches: "",
      canvasLongDimension: "",
      canvasShortDimension: "",
      canvasWidth: "",
      canvasHeight: "",
      xCenter: "",
      yCenter: "",
      canvasAspectRatio: 0,
      portraitCanvasLongDimension: "",
      portraitCanvasShortDimension: "",
      imageLongDimension: "",
      imageShortDimension: "",
      imageWidth: "",
      imageHeight: "",
      imageAspectRatio: 0,
      border: "",
      scaleFactor: "",
      imageDPI: "",
      dpiWarning: false,
      fullFrame: false,
      imageOrientation: "",
      canvasOrientation: "",
      canvasPortrait: "",
      angle: 0,
      frame: false,
      frameWidth: "",
      woodFrameWidth: "",
      fin: {
        dpi: 200,
        border: 0,
        canvasWidth: 0,
        canvasHeight: 0,
        overlayWidth: 0,
        overlayHeight: 0,
        angle: 0
      }
    };
  },
  mounted() {
    this.getImageInfo();
    this.setImageOrientation();
    this.setInitialCanvasOrientation();
    this.dpiCheck();
    if (this.isExhibitionPrints) {
      this.getFrameInfo();
    }
    this.defaultPrintSize();
    this.drawCanvas();

    this.$root.$on("setBorder", border => this.setBorders(border));
  },
  computed: {
    canvasStyles() {
      return {
        border: "1px solid #666",
        maxWidth: "100%",
        display: "block",
        margin: "0 auto",
        padding: this.border + "%"
      };
    }
  },
  methods: {
    // Takes the Print Size info from the Product Size select dropdown.
    getImageInfo() {
      let size = document.querySelector("select#size");
      size.addEventListener("change", () => {
        this.getImageInfo();
        this.setImageOrientation();
        this.setInitialCanvasOrientation();
        this.dpiCheck();
        this.defaultPrintSize();
        this.drawCanvas();
        this.setBorders(border);
      });
      this.widthHeight = size.selectedOptions[0].value.split("x");
      this.canvasLongInches = parseInt(this.widthHeight[1]);
      this.canvasShortInches = parseInt(this.widthHeight[0]);
      this.canvasLongDimension = 1000;
      this.canvasMultiplier = 1000 / parseInt(this.widthHeight[1]);
      this.canvasShortDimension = Math.round(
        parseInt(this.widthHeight[0]) * this.canvasMultiplier
      );
      this.canvasAspectRatio =
        this.canvasShortDimension / this.canvasLongDimension;
    },

    drawCanvas() {
      //   this.$root.processingImage = true;
      const image = new window.Image();
      image.src = this.$refs.currentimage.src;
      image.onload = () => {
        // set image only when it is loaded
        this.centerImage();
        let canvas = this.$refs.canvas;
        this.ctx = canvas.getContext("2d");
        let image = this.$refs.currentimage;
        this.ctx.drawImage(
          image,
          this.xCenter,
          this.yCenter,
          this.imageWidth,
          this.imageHeight
        );
      };
    },

    defaultPrintSize() {
      // Default (Not Fullframe)
      this.fullFrame = false;

      if ("portrait" === this.imageOrientation) {
        // Portrait
        if (this.imageAspectRatio < this.canvasAspectRatio) {
          this.fin.overlayHeight = this.canvasLongInches * this.fin.dpi;
          this.fin.overlayWidth =
            this.fin.overlayHeight * this.imageAspectRatio;
        } else {
          this.fin.overlayWidth = this.canvasShortInches * this.fin.dpi;
          this.fin.overlayHeight =
            this.fin.overlayWidth / this.imageAspectRatio;
        }
      } else {
        // Landscape
        if (this.imageAspectRatio < this.canvasAspectRatio) {
          this.fin.overlayWidth = this.canvasLongInches * this.fin.dpi;
          this.fin.overlayHeight =
            this.fin.overlayWidth * this.imageAspectRatio;
        } else {
          this.fin.overlayHeight = this.canvasShortInches * this.fin.dpi;
          this.fin.overlayWidth =
            this.fin.overlayHeight / this.imageAspectRatio;
        }
      }
    },
    setImageOrientation() {
      if (this.image.editor_image.width < this.image.editor_image.height) {
        this.imageOrientation = "portrait";
        // Portrait.
        this.imageAspectRatio = Number(
          (
            this.image.editor_image.width / this.image.editor_image.height
          ).toFixed(4)
        );
        this.imageHeight = 500;
        this.imageWidth = Math.floor(this.imageHeight * this.imageAspectRatio);
      } else if (
        this.image.editor_image.width > this.image.editor_image.height
      ) {
        // Landscape
        this.imageOrientation = "landscape";
        this.imageAspectRatio = Number(
          (
            this.image.editor_image.height / this.image.editor_image.width
          ).toFixed(4)
        );
        this.imageWidth = this.canvasLongDimension;
        this.imageHeight = Math.floor(this.imageWidth * this.imageAspectRatio);
      } else if (
        //   Square
        (this.image.editor_image.width = this.image.editor_image.height)
      ) {
        this.imageOrientation = "square";
        this.imageHeight = this.imageWidth = this.canvasShortDimension;
      }
    },
    updateImageOrientation() {
      if (
        "portrait" === this.canvasOrientation &&
        "landscape" === this.imageOrientation
      ) {
        this.imageWidth = this.canvasWidth;
        this.imageHeight = this.imageWidth * this.imageAspectRatio;
      } else if (
        "landscape" === this.canvasOrientation &&
        "portrait" === this.imageOrientation
      ) {
        this.imageWidth = this.canvasHeight * this.imageAspectRatio;
        this.imageHeight = this.canvasHeight;
      } else if (
        "landscape" === this.canvasOrientation &&
        "landscape" === this.imageOrientation
      ) {
        this.imageWidth = this.canvasWidth;
        this.imageHeight = this.imageWidth * this.imageAspectRatio;
      } else if (
        "portrait" === this.canvasOrientation &&
        "portrait" === this.imageOrientation
      ) {
        this.imageWidth = this.canvasHeight * this.imageAspectRatio;
        this.imageHeight = this.canvasHeight;
      }
    },
    setInitialCanvasOrientation() {
      if ("portrait" === this.imageOrientation) {
        // Portrait
        this.canvasOrientation = "portrait";
        this.canvasPortrait = true;
        this.canvasWidth = this.canvasShortDimension / 2;
        this.canvasHeight = 500;
        // Final print dimensions.
        this.fin.canvasWidth = this.canvasShortInches * this.fin.dpi;
        this.fin.canvasHeight = this.canvasLongInches * this.fin.dpi;
      } else if ("landscape" === this.imageOrientation) {
        //Landscape
        this.canvasOrientation = "landscape";
        this.canvasPortrait = false;
        this.canvasWidth = this.canvasLongDimension;
        this.canvasHeight = this.canvasShortDimension;
        // Final print dimensions.
        this.fin.canvasWidth = this.canvasLongInches * this.fin.dpi;
        this.fin.canvasHeight = this.canvasShortInches * this.fin.dpi;
      } else if ("square" === this.imageOrientation) {
        //   Square
        this.canvasOrientation = "square";
        this.canvasPortrait = false;
        this.canvasWidth = this.canvasLongDimension;
        this.canvasHeight = this.canvasShortDimension;
      }
    },
    toggleCanvasOrientation() {
      if (true === this.canvasPortrait) {
        //Set -> Landscape
        this.canvasOrientation = "landscape";
        this.canvasPortrait = false;
        this.canvasWidth = this.canvasLongDimension;
        this.canvasHeight = this.canvasShortDimension;

        // Final print dimensions.
        this.fin.canvasWidth = this.canvasLongInches * this.fin.dpi;
        this.fin.canvasHeight = this.canvasShortInches * this.fin.dpi;
      } else {
        //Set -> Portrait
        this.canvasOrientation = "portrait";
        this.canvasPortrait = true;
        this.canvasWidth = this.canvasShortDimension / 2;
        this.canvasHeight = 500;

        // Final print dimensions.
        this.fin.canvasWidth = this.canvasShortInches * this.fin.dpi;
        this.fin.canvasHeight = this.canvasLongInches * this.fin.dpi;
      }
      this.updateImageOrientation();
      this.drawCanvas();
    },
    centerImage() {
      //   Determine X center
      if (this.imageWidth < this.canvasWidth) {
        this.xCenter = (this.canvasWidth - this.imageWidth) / 2;
      } else if (this.imageWidth > this.canvasWidth) {
        this.xCenter = (this.imageWidth - this.canvasWidth) / -2;
      } else if (this.imageWidth === this.canvasWidth) {
        this.xCenter = 0;
      }
      //   Determine Y center
      if (this.imageHeight < this.canvasHeight) {
        this.yCenter = (this.canvasHeight - this.imageHeight) / 2;
      } else if (this.imageHeight > this.canvasHeight) {
        this.yCenter = (this.imageHeight - this.canvasHeight) / -2;
      } else if (this.imageHeight === this.canvasHeight) {
        this.yCenter = 0;
      }
    },
    setFullFrame() {
      // Set -> Fullframe
      this.ctx.clearRect(0, 0, this.canvasWidth, this.canvasHeight);

      if (false === this.fullFrame) {
        this.fullFrame = true;

        if ("portrait" === this.imageOrientation) {
          // Portrait
          // Aspect ratio is equal.
          if (this.imageAspectRatio === this.canvasAspectRatio) {
            this.imageHeight = this.canvasLongDimension / 2;
            this.imageWidth = this.canvasShortDimension / 2;
          } else if (this.imageAspectRatio < this.canvasAspectRatio) {
            //   Image is DSLR, Canvas is Standard.
            //   Editor
            this.imageWidth = this.canvasShortDimension / 2;
            this.imageHeight = this.imageWidth / this.imageAspectRatio;
            // Fin Output
            this.fin.overlayWidth = this.canvasShortInches * this.fin.dpi;
            this.fin.overlayHeight =
              this.fin.overlayWidth / this.imageAspectRatio;
          } else {
            //   Image is Standard, Canvas is DSLR.
            //   Editor
            this.imageHeight = this.canvasLongDimension / 2;
            this.imageWidth = this.imageHeight / this.imageAspectRatio;
            // Fin out put.
            this.fin.overlayHeight = this.canvasLongInches * this.fin.dpi;
            this.fin.overlayWidth =
              this.fin.overlayHeight * this.imageAspectRatio;
          }
        } else {
          // Landscape
          // Aspect ratio is equal.
          if (this.imageAspectRatio === this.canvasAspectRatio) {
            this.imageHeight = this.canvasShortDimension;
            this.imageWidth = this.canvasLongDimension;
          } else if (this.imageAspectRatio < this.canvasAspectRatio) {
            //   Image is DSLR, Canvas is Standard.
            //   Editor
            this.imageHeight = this.canvasShortDimension;
            this.imageWidth = this.imageHeight / this.imageAspectRatio;

            // Fin Output
            this.fin.overlayHeight = this.canvasShortInches * this.fin.dpi;
            this.fin.overlayWidth =
              this.fin.overlayHeight / this.imageAspectRatio;
          } else {
            //   Image is Standard, Canvas is DSLR.
            //   Editor
            this.imageWidth = this.canvasLongDimensions;
            this.imageHeight = this.imageWidth * this.imageAspectRatio;

            // Fin output.
            this.fin.overlayWidth = this.canvasLongInches * this.fin.dpi;
            this.fin.overlayHeight =
              this.fin.overlayWidth * this.imageAspectRatio;
          }
        }
        this.drawCanvas();
      } else {
        //   Revert to default.
        this.fullFrame = false;
        this.ctx.clearRect(0, 0, this.canvasWidth, this.canvasHeight);
        this.setImageOrientation();
        this.drawCanvas();
        this.defaultPrintSize();
      }
    },
    //   Set and Reflect Angle
    rotate() {
      this.fin.angle = (this.fin.angle + 90) % 360;
      this.angle = (this.angle + 90) % 360;

      // Canvas rotate methods
      this.ctx.save();
      this.ctx.clearRect(0, 0, this.canvasWidth, this.canvasHeight);
      this.ctx.translate(this.canvasWidth / 2, this.canvasHeight / 2);
      this.ctx.rotate((this.angle * Math.PI) / 180);
      this.ctx.drawImage(
        this.$refs.currentimage,
        -(this.imageWidth / 2),
        -(this.imageHeight / 2),
        this.imageWidth,
        this.imageHeight
      );
      this.ctx.restore();
    },

    dpiCheck() {
      this.imageDPI = Math.round(
        this.image.original.width / this.canvasLongInches
      );
      parseInt(this.imageDPI);
      if (200 > this.imageDPI) {
        this.dpiWarning = true;
      } else {
        this.dpiWarning = false;
      }
    },
    setBorders(border) {
      // Final border width *2 for Intervention.
      if (border) {
        this.fin.border = border * this.fin.dpi * 2;
      } else {
        this.fin.border = 0;
      }

      //Sets a 3/4" rabbit on frames (solely cosmetic for the editor).
      let frameRabbit;
      if (true === this.frame) {
        frameRabbit =
          0.75 * (this.canvasLongDimension / this.canvasLongInches) * 0.1;
      } else {
        frameRabbit = 0;
      }
      //If a border is selected...
      //Set border %
      if (border) {
        border =
          border * (this.canvasLongDimension / this.canvasLongInches) * 0.1;
      } else {
        border = 0;
      }
      //
      this.border = border + frameRabbit;
    },
    getFrameInfo() {
      let interval = setInterval(() => {
        let frameSelect = document.querySelector(
          "#component_options_1592007075 select"
        );
        // Wait till frame select is loaded to run these functions.
        if (frameSelect) {
          clearInterval(interval);

          let frame = document.querySelector("select#frame-style");
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
                this.frameWidth = this.imageDPI + "px";
                this.frame = true;
                this.setBorders();
                break;
              case "gray":
                this.$refs.frame.classList = "frame";
                this.$refs.frame.classList.add("frame-gray", "active");
                this.woodFrameWidth = this.imageDPI + "px";
                this.frame = true;
                this.setBorders();
                break;
              case "rustic":
                this.$refs.frame.classList = "frame";
                this.$refs.frame.classList.add("frame-rustic", "active");
                this.woodFrameWidth = this.imageDPI + "px";
                this.frame = true;
                this.setBorders();
                break;
              case "natural":
                this.$refs.frame.classList = "frame";
                this.$refs.frame.classList.add("frame-natural", "active");
                this.woodFrameWidth = this.imageDPI + "px";
                this.frame = true;
                this.setBorders();
                break;
              case "madera":
                this.$refs.frame.classList = "frame";
                this.$refs.frame.classList.add("frame-ply", "active");
                this.woodFrameWidth = this.imageDPI + "px";
                this.frame = true;
                this.setBorders();
                break;
              default:
                this.frame = false;
                this.setBorders();
                this.$refs.frame.classList = "";
            }
          });
        }
      });
    }
  },
  watch: {
    fin: {
      handler(value) {
        let image = this.$root.images.find(image => image.id === this.image.id);

        this.$set(image, "transformations", value);
      },
      deep: true,
      immediate: true
    }
  }
};
</script>

<style lang="scss" scoped>
.stage {
  position: relative;
  width: fit-content;
  margin: 0 auto;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0px 0px 7px 2px rgba(0, 0, 0, 0.5);
}

.frame {
  display: none;
  border-width: 30px;
  border-color: transparent;
  border-style: solid;
  position: absolute;
  width: 100%;
  height: 100%;
  z-index: 100;
}
.frame-black {
  border-color: #000;
}
.frame-gray {
  border-image: url(https://flauntyoursite.dev/wp-content/uploads/2020/07/gray-frame.png)
    30 round;
}
.frame-rustic {
  border-image: url(https://flauntyoursite.dev/wp-content/uploads/2020/07/rustic-frame.png)
    30 round;
}
.frame-natural {
  border-image: url(https://flauntyoursite.dev/wp-content/uploads/2020/07/natural-frame.png)
    30 round;
}
.frame-ply {
  border-image: url(https://flauntyoursite.dev/wp-content/uploads/2020/07/plywood-frame.png)
    30 round;
}
.frame.active {
  display: block;
}

#mat {
  border: 10px solid #fff;
  width: 100%;
  height: 100%;
  position: absolute;
  top: 0;
  z-index: 10;
  border-style: solid inset solid solid;
  left: 0;
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
  color: green;
  margin-top: 1em;
}

.warning {
  position: absolute;
  width: 100%;
  height: 100%;
  z-index: 100;
  color: orangered;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  div {
    width: 50%;
    color: #333;
    padding: 1em;
    border: 5px solid #f05228;
    border-radius: 1em;
    background-color: #fff;
    button {
      border: 1px solid orangered;
      &:hover {
        border: 1px solid orangered;
      }
    }
  }
}
</style>
