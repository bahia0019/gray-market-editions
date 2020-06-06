<template>
  <div>
    <div
      class="stage"
      :class="{
                'processing-image': $root.processingImage,
            }"
    >
      <div ref="frame" class="frame"></div>
      <div id="canvas" :style="canvasStyles">
        <!-- <div id="mat"></div> -->
        <img
          v-if="portrait"
          :width="imageShortDimension"
          :height="imageLongDimension"
          :style="imageStyles"
          :src="image.editor_image.url"
        />
        <img
          v-else
          :width="imageLongDimension"
          :height="imageShortDimension"
          :style="imageStyles"
          :src="image.editor_image.url"
        />
      </div>
      <svg viewBox="0 0 32 32" class="spinner">
        <path
          d="M32 12h-12l4.485-4.485c-2.267-2.266-5.28-3.515-8.485-3.515s-6.219 1.248-8.485 3.515c-2.266 2.267-3.515 5.28-3.515 8.485s1.248 6.219 3.515 8.485c2.267 2.266 5.28 3.515 8.485 3.515s6.219-1.248 8.485-3.515c0.189-0.189 0.371-0.384 0.546-0.583l3.010 2.634c-2.933 3.349-7.239 5.464-12.041 5.464-8.837 0-16-7.163-16-16s7.163-16 16-16c4.418 0 8.418 1.791 11.313 4.687l4.687-4.687v12z"
        />
      </svg>
    </div>

    <div class="tray">
      <div :class="{ warning: dpiWarning }">Your image is {{ imageDPI }} dpi</div>
      <edit-tools
        :fullFrame="fullFrame"
        @fullFrameClick="setFullFrame"
        @setBorder="setBorders"
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
    }
  },
  data() {
    return {
      canvasLongInches: "",
      canvasShortInches: "",
      canvasLongDimension: "",
      canvasShortDimension: "",
      canvasAspectRatio: 0,
      portraitCanvasLongDimension: "",
      portraitCanvasShortDimension: "",
      imageLongDimension: "",
      imageShortDimension: "",
      imageAspectRatio: 0,
      canvasWidth: "",
      canvasHeight: "",
      borderTop: "",
      borderRight: "",
      borderBottom: "",
      borderLeft: "",
      paddingTop: "",
      paddingRight: "",
      paddingBottom: "",
      paddingLeft: "",
      scaleFactor: "",
      imageDPI: "",
      dpiWarning: false,
      fullFrame: false,
      portrait: "",
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
        fullFrame: false,
        angle: 0
      }
    };
  },
  mounted() {
    this.getImageInfo();
    this.setOrientation();
    this.setCanvasOrientation();
    this.dpiCheck();
    this.getFrameInfo();
    this.defaultPrintSize();
  },
  updated() {},
  computed: {
    imageStyles() {
      return {
        height: this.imageHeight + "px",
        width: this.imageWidth + "px",
        position: "",
        transform: `rotate(${this.angle}deg)`
        // transform: `scale(${this.scaleFactor})`
      };
    },
    canvasStyles() {
      return {
        border: "1px solid #666",
        backgroundColor: "#fff",
        width: this.canvasWidth,
        height: this.canvasHeight,
        paddingTop: this.borderTop + "%",
        paddingRight: this.borderRight + "%",
        paddingBottom: this.borderBottom + "%",
        paddingLeft: this.borderLeft + "%"
      };
    }
  },
  methods: {
    // Takes the Print Size info from the Product Size select dropdown.
    getImageInfo() {
      let size = document.querySelector("select#size");
      size.addEventListener("change", () => {
        this.getImageInfo();
        this.setOrientation();
        this.setCanvasOrientation();
        this.dpiCheck();
        this.defaultPrintSize();
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
    defaultPrintSize() {
      // Default (Not Fullframe)
      this.fullFrame = this.fin.fullFrame = false;
      this.scaleFactor = 1;

      if (true === this.portrait) {
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
    setOrientation() {
      if (this.image.editor_image.height > this.image.editor_image.width) {
        this.portrait = true;
        // Portrait.
        this.imageAspectRatio = Number(
          (
            this.image.editor_image.width / this.image.editor_image.height
          ).toFixed(4)
        );

        this.imageLongDimension = 500;
        this.imageShortDimension = Math.floor(
          this.imageLongDimension * this.imageAspectRatio
        );
      } else {
        // Landscape
        this.portrait = false;
        this.imageAspectRatio = Number(
          (
            this.image.editor_image.height / this.image.editor_image.width
          ).toFixed(4)
        );
        this.imageLongDimension = this.canvasLongDimension;
        this.imageShortDimension = Math.floor(
          this.canvasLongDimension * this.imageAspectRatio
        );
      }
    },
    setCanvasOrientation() {
      if (true === this.portrait) {
        // Portrait
        this.canvasPortrait = true;
        this.canvasWidth = this.canvasShortDimension / 2;
        this.canvasHeight = 500;
        // Final print dimensions.
        this.fin.canvasWidth = this.canvasShortInches * this.fin.dpi;
        this.fin.canvasHeight = this.canvasLongInches * this.fin.dpi;
      } else {
        this.canvasPortrait = false;
        //Landscape
        this.canvasWidth = this.canvasLongDimension;
        this.canvasHeight = "";
        // Final print dimensions.
        this.fin.canvasWidth = this.canvasLongInches * this.fin.dpi;
        this.fin.canvasHeight = this.canvasShortInches * this.fin.dpi;
      }
    },
    toggleCanvasOrientation() {
      if (true === this.canvasPortrait) {
        //Set -> Landscape
        this.canvasPortrait = false;
        this.canvasWidth = this.canvasLongDimension + "px";
        this.canvasHeight = "initial";
        // Final print dimensions.
        this.fin.canvasWidth = this.canvasLongInches * this.fin.dpi;
        this.fin.canvasHeight = this.canvasShortInches * this.fin.dpi;
      } else {
        //Set -> Portrait
        this.canvasPortrait = true;
        this.canvasWidth = this.canvasShortDimension / 2 + "px";
        this.canvasHeight = this.canvasLongDimension / 2 + "px";
        // Final print dimensions.
        this.fin.canvasWidth = this.canvasShortInches * this.fin.dpi;
        this.fin.canvasHeight = this.canvasLongInches * this.fin.dpi;
      }
    },
    setFullFrame() {
      // Set -> Fullframe
      if (false === this.fullFrame) {
        this.fullFrame = this.fin.fullFrame = true;
        if (true === this.portrait) {
          // Set scale factor for Editor
          this.scaleFactor =
            this.portraitCanvasShortDimension / this.imageShortDimension;

          //Provide image size info for Final Print.
          if (this.imageAspectRatio < this.canvasAspectRatio) {
            this.fin.overlayWidth = this.canvasShortInches * this.fin.dpi;
            this.fin.overlayHeight =
              this.fin.overlayWidth / this.imageAspectRatio;
          } else {
            this.fin.overlayHeight = this.canvasLongInches * this.fin.dpi;
            this.fin.overlayWidth =
              this.fin.overlayHeight * this.imageAspectRatio;
          }
        } else {
          // Set scale factor for Editor
          this.scaleFactor =
            this.canvasShortDimension / this.imageShortDimension; //GOOD!

          //Provide image size info for Final Print.
          if (this.imageAspectRatio < this.canvasAspectRatio) {
            this.fin.overlayHeight = this.canvasShortInches * this.fin.dpi;
            this.fin.overlayWidth =
              this.fin.overlayHeight / this.imageAspectRatio;
          } else {
            this.fin.overlayWidth = this.canvasLongInches * this.fin.dpi;
            this.fin.overlayHeight =
              this.fin.overlayWidth * this.imageAspectRatio;
          }
        }
      } else {
        //   Revert to default.
        this.defaultPrintSize();
      }
    },
    //   Set and Reflect Angle
    rotate() {
      this.fin.angle = (this.fin.angle + 90) % 360;
      this.angle = (this.angle + 90) % 360;
      // this.setOrientation()
    },

    dpiCheck() {
      this.imageDPI = Math.round(
        this.image.original.width / this.canvasLongInches
      );
      console.log(this.imageDPI);
      parseInt(this.imageDPI);
      if (200 > this.imageDPI) {
        this.dpiWarning = true;
      } else {
        this.dpiWarning = false;
      }
    },
    setBorders(border) {
      // Final border width *2 for Intervention.
      this.fin.border = border * this.fin.dpi * 2;

      //Sets a 3/4" rabbit on frames (solely cosmetic for the editor).
      let frameRabbit = 0;
      /**
       * Come back to this and solve for frames.
       */
      // if (true === this.frame) {
      //     frameRabbit =
      //         0.75 * (this.canvasLongDimension / this.canvasLongInches) * 0.1
      // } else {
      //     frameRabbit = 0
      // }

      let aspectFactor =
        ((this.canvasAspectRatio - this.imageAspectRatio) / 2) * 100; //GOOD!

      //if no borders are selected, checks for difference in aspect ratio.
      if (true === this.portrait) {
        //Adds Frame Rabbit value and Aspect ratio
        this.paddingTop = this.paddingBottom = frameRabbit;
        this.paddingLeft = this.paddingRight = aspectFactor + frameRabbit;
      } else {
        //Adds Frame Rabbit value and Aspect ratio
        this.paddingTop = this.paddingBottom = aspectFactor + frameRabbit;
        this.paddingLeft = this.paddingRight = frameRabbit;
      }
      //Set Borders
      this.borderTop = this.paddingTop;
      this.borderRight = this.paddingRight;
      this.borderBottom = this.paddingBottom;
      this.borderLeft = this.paddingLeft;

      //If a border is selected...
      //Set border %
      border =
        border * (this.canvasLongDimension / this.canvasLongInches) * 0.1;

      if (border) {
        //If aspect ration padding is 0 or less than selected border, use the border.
        if (0 === this.paddingTop || border > this.paddingTop) {
          this.borderTop = border + frameRabbit;
          //If the border is less than the padding, subtract the border from the padding.
        } else if (border < this.paddingTop) {
          this.borderTop = this.paddingTop + frameRabbit;
        }
        if (0 === this.paddingRight || border > this.paddingRight) {
          this.borderRight = border + frameRabbit;
        } else if (border < this.paddingRight) {
          this.borderRight = this.paddingRight + frameRabbit;
        }
        if (0 === this.paddingBottom || border > this.paddingBottom) {
          this.borderBottom = border + frameRabbit;
        } else if (border < this.paddingBottom) {
          this.borderBottom = this.paddingBottom + frameRabbit;
        }
        if (0 === this.paddingLeft || border > this.paddingLeft) {
          this.borderLeft = border + frameRabbit;
        } else if (border < this.paddingLeft) {
          this.borderLeft = this.paddingLeft;
        }
      }
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
            this.frameWidth = this.imageDPI + "px";
            this.frame = true;
            this.setBorders();
            break;
          case "down-n-dirty":
            this.$refs.frame.classList = "frame";
            this.$refs.frame.classList.add("frame-down-n-dirty", "active");
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
  },
  watch: {
    fin: {
      handler(value) {
        let image = this.$root.images.find(
          image => image.id === this.image.id
        )

        this.$set(image, 'transformations', value);
      },
      deep: true
    }
  },
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
.frame-down-n-dirty {
  border-image: url(https://img.freepik.com/free-photo/wooden-textured-background_53876-14865.jpg?size=626&ext=jpg)
    30 round;
}
.frame.active {
  display: block;
}

#canvas {
  display: flex;
  align-items: center;
  justify-content: center;
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
}

.warning {
  color: orangered;
}
</style>
